const fs = require('fs');

const usage = () => {
    console.log('Usage: node main.js <path_to_file>');
    process.exit(1);
}

const print = (sortedLinks) => console.log(sortedLinks.join('\n'));

const main = (fileName) => {
    try {
        const content = fs.readFileSync(fileName, 'utf-8');

        const linkRegex = /['"](https?:\/\/[^"']+)['"]/gi;
        const matches = content.matchAll(linkRegex);

        const cleanLinks = [...matches].map(match => match[1]);
        const uniqueLinks = Array.from(new Set(cleanLinks));
        const sortedLinks = uniqueLinks.sort();

        print(sortedLinks);
    } catch (error) {
        console.error('Error reading the file:', error.message);
    }
}

const fileName = process.argv[2];
if (!fileName) {
    usage();
}

main(fileName);
