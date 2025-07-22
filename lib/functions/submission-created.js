require('dotenv').config()
const { BUTTONDOWN_API_KEY } = process.env

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
        Authorization: `Token ${BUTTONDOWN_API_KEY}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ email }),
    })

    const data = await response.json()
    console.log(`Submitted to Buttondown:\n${JSON.stringify(data)}`)

    return {
      statusCode: 200,
      body: JSON.stringify({ success: true }),
    }
  } catch (error) {
    console.error('Buttondown API error:', error)
    return {
      statusCode: 422,
      body: JSON.stringify({ error: error.message }),
    }
  }
}
