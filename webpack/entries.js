/*
This file contains all the entries for Webpack (both for development and production).
It is recommended to group assets that are always used together with an index.js file (including style files).
For example, you can have an entry "Main" that includes all assets that are used everywhere on the front-end.
*/
const entries = {
  'child-theme-main': './assets/child-theme-main/index.js',
  'child-theme-editor': './assets/child-theme-editor/index.js',
  // 'bootstrap': './assets/bootstrap/index.js',
  'maak-toekomst': './assets/maak-toekomst/index.js',
};
module.exports = entries;
