const fs = require('fs');
const content = fs.readFileSync('app/Views/admin/settings.php', 'utf8');
const regex = /<script\b[^>]*>([\s\S]*?)<\/script>/gi;
let match;
let allScripts = '';
while ((match = regex.exec(content)) !== null) {
    allScripts += match[1] + '\n\n';
}
fs.writeFileSync('scripts.js', allScripts);
