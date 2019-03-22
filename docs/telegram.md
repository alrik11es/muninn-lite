  The Telegram Agent receives and collects events and sends them via [Telegram](https://telegram.org/).
  It is assumed that events have either a `text`, `photo`, `audio`, `document` or `video` key. You can use the EventFormattingAgent if your event does not provide these keys.
  The value of `text` key is sent as a plain text message. You can also tell Telegram how to parse the message with `parse_mode`, set to either `html` or `markdown`.
  The value of `photo`, `audio`, `document` and `video` keys should be a url whose contents will be sent to you.
  **Setup**
  * Obtain an `auth_token` by [creating a new bot](https://telegram.me/botfather).
  * If you would like to send messages to a public channel:
    * Add your bot to the channel as an administrator
  * If you would like to send messages to a group:
    * Add the bot to the group
  * If you would like to send messages privately to yourself:
    * Open a conservation with the bot by visiting https://telegram.me/YourHuginnBot
  * Send a message to the bot, group or channel.
  * Select the `chat_id` from the dropdown.
  **Options**
  * `caption`: caption for a media content (0-200 characters)
  * `disable_notification`: send a message silently in a channel
  * `disable_web_page_preview`: disable link previews for links in a text message
  * `long_message`: truncate (default) or split text messages and captions that exceed Telegram API limits. Markdown and HTML tags can't span across messages and, if not opened or closed properly, will render as plain text.
  * `parse_mode`: parse policy of a text message
  See the official [Telegram Bot API documentation](https://core.telegram.org/bots/api#available-methods) for detailed info.
MD