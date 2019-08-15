# Financial Planner Web

### Installation instructions

This project uses HTML, CSS, JS and images minifier for building and live-server (BrowserSync), SASS, Bootstrap and Font-Awesome for developing. Any dependency version can be changed to convenience.

1. So, you need to have Node.js installed in your computer and then, install node-sass globally.

`npm install -g node-sass`

2. Then, install the dependencies.

`npm install`

### Running the server

All you need to do is run

`npm start`

That will fire up the live-server and node-sass to watch for changes. It will also open a web browser at **localhost:3000**

### Usage

1. All SCSS files inside the **scss/** folder are processed and output to the **css/** folder, with the same name of the scss file.

2. To add CSS or Javascript files to your HTML files, you must make sure to put them inside their corresponding tags so they all can be unified and minified later.
`build:css css/main.css` and `build:js js/main.js`.

3. The images folder name must match the one in the **imagemin** script your **package.json** file so they can later be minified.


### Deployment

To get the website bundled and ready for deployment, you must do the following:

1. Add all the HTML files you created to the "usemin" script in your package.json file.
For each HTML file, you must use this script:
`usemin index.html -d dist --htmlmin -o dist/index.html`
Remember to add a **&&** between files. In example:
`usemin index.html -d dist --htmlmin -o dist/index.html && usemin contact-us.html -d dist --htmlmin -o dist/contact-us.html`
The script is pretty self explanatory. It'll take the index.html file, minify it and its CSS and Javascript files and put the output in dist/index.html.

3. After you have all your files set, you can run:
`npm run build`

That will create a dist/ folder where you have a light version of the website, ready to deploy.