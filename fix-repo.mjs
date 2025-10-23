import fs from 'fs/promises';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const pkgPath = path.resolve(__dirname, 'vendor/nativephp/electron/resources/js/package.json');

const pkgData = JSON.parse(await fs.readFile(pkgPath, 'utf-8'));
pkgData.repository = {
    type: 'git',
    url: 'https://github.com/five-angles-company/printio.git',
};

await fs.writeFile(pkgPath, JSON.stringify(pkgData, null, 2), 'utf-8');
console.log('✅ repository added to electron-builder package.json');
