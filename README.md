# Client Style

Custom CSS & JavaScript injection for the ExpressionEngine 7 control panel. Brand the backend for your clients with built-in snippet presets or full custom code.

## Features

- Link external CSS/JS files or write inline code
- **Common Styles** modal with one-click CSS snippets (sidebar, buttons, headers, login page, etc.)
- **Common Scripts** modal with interactive JS snippets (hide/rename nav items, sections, welcome banners, etc.)
- Settings stored per-site for MSM compatibility
- Side-by-side CSS/JS editor layout

## Installation

1. Copy `system/user/addons/client_style/` to your EE site's `system/user/addons/` directory
2. Go to **Add-ons** in the control panel
3. Find **Client Style** and click **Install**
4. Access settings via **Add-ons → Client Style**

## Requirements

- ExpressionEngine 7.x

## Documentation

Full documentation, changelog and support: [reizoko.jp/en/addons/client-style.html](https://www.reizoko.jp/en/addons/client-style.html)

## Security

Anything entered in the Custom JavaScript, Custom CSS and file URL fields is injected into every control panel page for every user. Settings are restricted to Super Admins, and code is stored exactly as entered (it is not XSS-cleaned), so only give Super Admin access to people you trust.

- File URLs must be plain `http(s)://` URLs. Anything else is ignored.
- The JavaScript file URL is fetched server-side (3 second timeout) and inlined into the control panel script, so the server needs `allow_url_fopen` enabled and the URL must be reachable from the server.

## License

Released under the [MIT License](LICENSE).

## Author

[Pontus Madsen](https://github.com/PontusMadsen) · more add-ons at [reizoko.jp](https://www.reizoko.jp/en/addons.html)
