# Change Log

## [NEXT VERSION]
[FIXED]
- PHP and JS coding style fix

## [v1.1.0] - [04 MAR 2021]

[FIXED]
- Fnugg API URL is placed under `Api()::constructor()` method.
- `DIRECTORY_SEPARATOR` predefined PHP constant used instead of slash(`/`).
- `empty()` check added to `Helpers::trans_id()` method.

[UPDATE]
- Fronent example elaborated in [README.md](README.md) file.

## [v1.0.0] - [03 MAR 2021]

[FEATURE]
- Introduced caching mechanism for API's as well as for the frontend.
- Used hash function to create transient key, see `Helpers::trans_id()` method.

[FIXED]
- Fixed return type for `render()` method.

## [v0.0.2] - [02 MAR 2021]

[FEATURE]
- Introduced `fnugg_frontend_self_api_search_params` filter hook in `Block\Block::render()`.
- Introduced `fnugg_frontend_render_html` action hook in `Block\Block::render()`.

[FIXED]
- Admin namespace changed to `Block`.
- Multiple instance issue fixed.
- Removed unnecessary packages.
- Added some CSS in editor CSS.
- Removed default frontend part.

## [v0.0.1] - [01 MAR 2021]

- Initial release.
