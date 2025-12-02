const crypto = require('crypto')

// Build an anonymous "client key" from IP + UA
function clientKey(headers = {}) {
  const ip =
    headers['x-nf-client-connection-ip'] ||
    headers['client-ip'] ||
    ''
  const ua = headers['user-agent'] || ''
  const raw = `${ip}::${ua}`

  return crypto.createHash('sha256').update(raw).digest('hex')
}

exports.handler = async event => {
  const ts = new Date().toISOString()
  const key = clientKey(event.headers || {})

  // One clean JSON line per hit; easy to copy out later
  console.log(
    JSON.stringify({
      type: 'feed_hit',
      feed: 'json',
      ts,
      client: key,
    })
  )

  const origin =
    process.env.DEPLOY_URL ||
    process.env.URL ||
    'https://www.tempertemper.net'

  // This is the REAL file Eleventy now writes
  const feedUrl = `${origin}/feeds/main-source.json`

  let upstream
  try {
    upstream = await fetch(feedUrl)
  } catch (error) {
    console.error('Error fetching upstream JSON feed:', error)
    return {
      statusCode: 502,
      headers: {
        'content-type': 'text/plain; charset=utf-8',
      },
      body: 'Upstream feed error',
    }
  }

  if (!upstream.ok) {
    const text = await upstream.text().catch(() => '')
    console.error('Upstream JSON feed error:', upstream.status, text)
    return {
      statusCode: 502,
      headers: {
        'content-type': 'text/plain; charset=utf-8',
      },
      body: 'Upstream feed error',
    }
  }

  // Legacy functions expect a string body
  const body = await upstream.text()

  return {
    statusCode: 200,
    headers: {
      'content-type': 'application/feed+json; charset=utf-8',
      'cache-control': 'public, max-age=300',
    },
    body,
  }
}
