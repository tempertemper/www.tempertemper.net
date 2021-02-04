---
title: iOS email set up
intro: |
    Setting an email account up on your iOS device seems fairly straightforward but there are some details that are worth getting right.
date: 2013-01-16
tags:
    - Email
---

Setting an email account up on your iOS device (Apple iPad, iPod or iPhone) seems fairly straightforward but there are some details it's worth getting right to enjoy the most seamless experience from device to device (if you're using your phone in conjunction with your laptop, for example).


## Opening the set-up

Open your settings app and tap and scroll down to 'Mail, Contacts, Calendars'. In the top group, labelled 'Accounts' tap 'Add Account'. Tap 'Other' at the bottom of the list, then 'Add Mail Account' on the next page.


## Your account details

The next screen needs some information:

- Name (your full name: this is the 'sender information' that recipients of your email see)
- Email (the email address of the account you're setting up)
- Password (the password associated with the email account you're setting up. I'll have sent you this)
- Description (your 'friendly name' for the email account -- something like "My account" or "My business account", or just the name of your business, for example)

Tap 'Next' in the top right once everything has been entered and it will check your account exists.


## More details

The next screen goes into a little more detail.

- First ensure the 'IMAP' button is selected at the top of the page
- Your Name, Email and Description will have automatically populated with the info you entered in the last step
- In the Incoming Mail Servers section, enter your incoming mail server name. Usually something like mail.example.com or imap.example.com
- Your username is your email address and your password should have automatically populated as you already entered it in the last step
- In the Outgoing Mail Servers section, enter outgoing mail server name. Usually something like smtp.example.com
- Enter your username (email address) and password again here

Once you've done all of that, hit 'Next' in the top-right of the screen and it will verify your settings.

On the next screen make sure that Mail is set to 'on' and Notes is set to 'off'.

Click 'Save' and it will add your account. Your mailbox is now set up to download emails to your iPhone/iPad! But don't stop there -- there are some extra details to sort out!


## Download settings

Firstly, that your email download settings are the way you want them:

- Go into your settings
- Tap on 'Mail, Contacts, Calendars'
- Tap 'Fetch New Data'
- Turn 'Push' off
- Select 'Every 15 Minutes', 'Every 30 Minutes', 'Hourly' or 'Manually', depending on your preference


## Folder mapping

Folder what!? Bear with me -- this bit's important!

By default all of your drafts, sent items and deleted items are stored on your phone, but that's no good if you want to pick up a draft you started on your phone on your laptop, or you're on your laptop and you want to double check what you said in that email you sent from your phone last week. So we want to use the drafts, sent and trash mailboxes on your server instead, so that they can be accessed from anywhere, just like your inbox.

First you'll want to make sure your drafts, sent and deleted email mailboxes are all connected. Open the Mail app and go into the account you've set up on the 'Accounts' section of the first page. This'll open the various folders that you have on your mail account. If it's a new mailbox that you've never used before it's likely you've only got a few: Sent, Drafts and Trash, on top of your inbox. All you have to do is open each folder. Simple! Now your phone knows those folders exist!

Now head into your settings again and find 'Mail, Contacts, Calendars'.

- Tap the mail account you've just set up
- Tap the 'Account' button with your email address just after it
- Scroll to the bottom and tap 'Advanced'
- In the 'Mailbox Behaviours' section at the top, tap 'Drafts Mailbox'
- Ensure the 'Drafts' folder at the bottom in the 'On the Server' is selected (not the one at the top in the 'On My iPhone' section)
- Head back to 'Advanced' and check that it says 'Drafts' to the right of the 'Drafts Mailbox' button
- Tap 'Sent Mailbox' and repeat the process, ensuring the 'Sent' at the top in the 'On the Server' is selected.
- Same for 'Deleted Mailbox'

While we're in there, just check that the Deleted Messages section is set to 'Remove > Never' and the 'Incoming Settings' section says that 'Use SSL' is on and 'Authentication' is set to 'Password'.

Ta-da! All done! It was a bit of a lengthy process but well worth it for a great email experience!
