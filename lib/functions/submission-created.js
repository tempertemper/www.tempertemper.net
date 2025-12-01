require('dotenv').config()

const { BUTTONDOWN_API_KEY } = process.env
const BUTTONDOWN_API_VERSION = '2025-06-01'
const BUTTONDOWN_API_BASE = 'https://api.buttondown.email/v1'
const SUBSCRIBE_FORM_NAME = 'newsletter'  // set to your signup form's name
const UNSUBSCRIBE_FORM_NAME = 'unsubscribe'

exports.handler = async event => {
  let payload

  try {
    payload = JSON.parse(event.body).payload
  } catch (error) {
    console.error('Failed to parse submission payload:', event.body)
    return {
      statusCode: 400,
      body: JSON.stringify({ error: 'Invalid submission format' }),
    }
  }

  const formName = payload.form_name
  const email = payload?.data?.email

  if (!email) {
    console.error('No email address in submission payload:', payload)
    return {
      statusCode: 400,
      body: JSON.stringify({ error: 'Missing email address' }),
    }
  }

  console.log(`Received a submission for form "${formName}": ${email}`)

  // Ignore other forms
  if (formName !== SUBSCRIBE_FORM_NAME && formName !== UNSUBSCRIBE_FORM_NAME) {
    console.log(`Ignoring form "${formName}"`)
    return {
      statusCode: 200,
      body: JSON.stringify({ success: true, ignored: true }),
    }
  }

  try {
    if (formName === SUBSCRIBE_FORM_NAME) {
      // Subscribe
      const response = await fetch(`${BUTTONDOWN_API_BASE}/subscribers`, {
        method: 'POST',
        headers: {
          Authorization: `Token ${BUTTONDOWN_API_KEY}`,
          'Content-Type': 'application/json',
          'X-API-Version': BUTTONDOWN_API_VERSION,
        },
        body: JSON.stringify({
          email_address: email,
        }),
      })

      const text = await response.text()

      if (!response.ok) {
        console.error('Buttondown API error (subscribe):', response.status, text)
        return {
          statusCode: 502,
          body: JSON.stringify({ error: 'Failed to add subscriber' }),
        }
      }

      console.log(`Subscribed in Buttondown:\n${text}`)

      return {
        statusCode: 200,
        body: JSON.stringify({ success: true, action: 'subscribed' }),
      }
    }

    if (formName === UNSUBSCRIBE_FORM_NAME) {
      // Unsubscribe
      const url = `${BUTTONDOWN_API_BASE}/subscribers/${encodeURIComponent(email)}`

      const response = await fetch(url, {
        method: 'PATCH',
        headers: {
          Authorization: `Token ${BUTTONDOWN_API_KEY}`,
          'Content-Type': 'application/json',
          'X-API-Version': BUTTONDOWN_API_VERSION,
        },
        body: JSON.stringify({
          email_address: email,
          type: 'unsubscribed',
        }),
      })

      const text = await response.text()

      // 200 updated; 404 not found; 404 is fine for UX
      if (!response.ok && response.status !== 404) {
        console.error('Buttondown API error (unsubscribe):', response.status, text)
        return {
          statusCode: 502,
          body: JSON.stringify({ error: 'Failed to unsubscribe' }),
        }
      }

      console.log(`Unsubscribe processed in Buttondown (status ${response.status}):\n${text}`)

      return {
        statusCode: 200,
        body: JSON.stringify({ success: true, action: 'unsubscribed' }),
      }
    }
  } catch (error) {
    console.error('Buttondown API error (network/other):', error)
    return {
      statusCode: 502,
      body: JSON.stringify({ error: error.message }),
    }
  }
}
