Banrepcultural Skin
========================

Installation
------------

See <https://www.mediawiki.org/wiki/Skin:Banrepcultural>.

### Configuration options

See [skin.json](skin.json).

Development
-----------

### Coding conventions

We strive for compliance with MediaWiki conventions:

<https://www.mediawiki.org/wiki/Manual:Coding_conventions>

Additions and deviations from those conventions that are more tailored to this
project are noted at:

<https://www.mediawiki.org/wiki/Reading/Web/Coding_conventions>

URL query parameters
--------------------

- `useskinversion`: Like `useskin` but for overriding the Banrepcultural skin version
  user preference and configuration. E.g.,
  http://localhost:8181?useskin=banrepcultural&useskinversion=2.

Skin preferences
----------------

Banrepcultural defines skin-specific user preferences. These are exposed on
Special:Preferences when the `BanrepculturalShowSkinPreferences` configuration is
enabled. The user's preference state for skin preferences is used for skin
previews and any other operation unless specified otherwise.

### Version

Banrepcultural defines a "version" preference to enable users who prefer the December
2019 version of Banrepcultural to continue to do so without any visible changes. This
version is called "Legacy Banrepcultural." The related preference defaults are
configurable via the configurations prefixed with `BanrepculturalDefaultSkinVersion`.
Version preference and configuration may be overridden by the `useskinversion`
URL query parameter.

### Hooks
See [hooks.txt](hooks.txt).
