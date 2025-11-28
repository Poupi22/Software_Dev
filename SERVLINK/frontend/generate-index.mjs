import { writeFileSync, readdirSync, statSync } from 'fs'

const assets = readdirSync('dist/client/assets')

const js = assets
  .filter(f => f.startsWith('index-') && f.endsWith('.js'))
  .sort((a, b) => statSync(`dist/client/assets/${b}`).size - statSync(`dist/client/assets/${a}`).size)[0]

const css = assets.find(f => f.endsWith('.css'))

const html = `<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>App</title>
    <link rel="stylesheet" crossorigin href="/assets/${css}">
    <script type="module" crossorigin src="/assets/${js}"></script>
  </head>
  <body>
    <div id="root"></div>
  </body>
</html>`

writeFileSync('dist/client/index.html', html)
console.log('✅ Generated index.html with', js, 'and', css)