const fs = require('fs');

// Read the content of asset-manifest.json
const assetManifestJson = fs.readFileSync('build/asset-manifest.json', 'utf-8');

// Parse JSON content to JavaScript object
const assetManifest = JSON.parse(assetManifestJson);

// Convert JavaScript object to PHP code representation
const prefix = `<?php\n$asset_manifest_json = <<<'HEREA'\n`;
const phpCode = JSON.stringify(assetManifest, null, 4).replace(/\n/g, '\n    ');
const post = `\nHEREA;\n`;

// Write the PHP code to asset-manifest.php
fs.writeFileSync('build/asset-manifest.php', prefix + phpCode + post);