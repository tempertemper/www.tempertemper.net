require('dotenv').config()
const { BUTTONDOWN_API_KEY } = process.env
const BUTTONDOWN_API_VERSION = '2025-06-01'

exports.handler = async event => {
  let email
  try {
    email = JSON.parse(event.body).payload.data.email
    console.log(`Received a submission: ${email}`)
  } catch (error) {
    console.error('Failed to parse submission payload:', event.body)
    return {
      statusCode: 400,
      body: JSON.stringify({ error: 'Invalid submission format' }),
    }
  }

  try {
    const response = await fetch('https://api.buttondown.email/v1/subscribers', {
      method: 'POST',
      headers: {
        Authorization: `Token ${ BUTTONDOWN_API_KEY }`,
        'Content-Type': 'application/json',
        'X-API-Version': BUTTONDOWN_API_VERSION,
      },
      body: JSON.stringify({
        email_address: email,
      }),
    })

    const text = await response.text()

    if (!response.ok) {
      console.error('Buttondown API error:', response.status, text)
      return {
        statusCode: 502,
        body: JSON.stringify({ error: 'Failed to add subscriber' }),
      }
    }

    console.log(`Submitted to Buttondown:\n${text}`)

    return {
      statusCode: 200,
      body: JSON.stringify({ success: true }),
    }
  } catch (error) {
    console.error('Buttondown API error (network/other):', error)
    return {
      statusCode: 502,
      body: JSON.stringify({ error: error.message }),
    }
  }
}
