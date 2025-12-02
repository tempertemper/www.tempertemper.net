function requestOrigin(headers = {}) {
  const host =
    headers['x-forwarded-host'] ||
    headers.host ||
    'www.tempertemper.net'

  const proto =
    headers['x-forwarded-proto'] ||
    'https'

  return `${proto}://${host}`
}

exports.handler = async event => {
  const ts = new Date().toISOString()

  console.log(
    JSON.stringify({
      type: 'feed_hit',
      feed: 'atom',
      ts,
    })
  )

  const origin = requestOrigin(event.headers || {})
  const feedUrl = `${origin}/feeds/main-source.xml`

  console.log(
    JSON.stringify({
      type: 'feed_debug',
      origin,
      feedUrl,
    })
  )

  let upstream
  try {
    upstream = await fetch(feedUrl)
  } catch (error) {
    console.error('Error fetching upstream Atom feed:', error)
    return {
      statusCode: 502,
      headers: {
        'content-type': 'text/plain; charset=utf-8',
      },
      body: 'Upstream feed error (network)',
    }
  }

  if (!upstream.ok) {
    const text = await upstream.text().catch(() => '')
    console.error(
      'Upstream Atom feed error:',
      upstream.status,
      text.slice(0, 200)
    )
    return {
      statusCode: 502,
      headers: {
        'content-type': 'text/plain; charset=utf-8',
      },
      body: `Upstream feed error (${upstream.status})`,
    }
  }

  const body = await upstream.text()

  return {
    statusCode: 200,
    headers: {
      'content-type': 'application/atom+xml; charset=utf-8',
      'cache-control': 'public, max-age=300',
    },
    body,
  }
}
