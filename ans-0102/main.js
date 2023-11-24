const fs = require('fs');

const usage = () => {
    console.log('Usage: node main.js <path_to_file>');
    process.exit(1);
}

const print = (groupedLinks) => {
    for (const origin in groupedLinks) {
        if (groupedLinks.hasOwnProperty(origin)) {
            const total = groupedLinks[origin].length;
            console.log(`origin=${origin} total=${total}`);
            console.log(groupedLinks[origin].join('\n'));
            console.log();
        }
    }
}

const groupLinksByOrigin = (links) => {
    const groupedLinks = {};

    links.forEach(link => {
        const url = new URL(link);
        const origin = `${url.protocol}//${url.hostname}`;
        if (!groupedLinks[origin]) {
            groupedLinks[origin] = [];
        }
        groupedLinks[origin].push(link);
    });

    return groupedLinks;
}

const main = (fileName) => {
    try {
        const content = fs.readFileSync(fileName, 'utf-8');

        const linkRegex = /['"](https?:\/\/[^"']+)['"]/gi;
        const matches = content.matchAll(linkRegex);

        const cleanLinks = [...matches].map(match => match[1]);
        const sortedLinks = cleanLinks.sort();
        const groupedLinks = groupLinksByOrigin(sortedLinks);

        print(groupedLinks);
    } catch (error) {
        console.error('Error reading the file:', error.message);
    }
}

const fileName = process.argv[2];

if (!fileName) {
    usage();
}

main(fileName);
