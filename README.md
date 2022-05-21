# [ScreenAPI](https://screenapi.cc/)
API to take screenshots of webpages.

## Usage
Usage is as simple as:

```
curl "https://screenapi.cc/400x800/github.com" -o screen.png
                           ^   ^        ^
                       width   height   url ("domain/path?query-string" format supported)
```

## Examples
```
# https://github.com
# 400x800 resolution
curl "https://screenapi.cc/400x800/github.com" -o screen.png

# https://github.com/mrcrypster
# 1200x800 resolution
curl "https://screenapi.cc/1200x800/github.com/mrcrypster" -o screen.png

# https://medium.com/search?q=php
# 600x600 resolution
curl "https://screenapi.cc/600x600/medium.com/search?q=php" -o screen.png
```
