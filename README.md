# Greenpeace Planet 4 Child Theme for GP Netherlands

Child theme for the Planet 4 Wordpress project.
The related master theme’s code lives at:

https://github.com/greenpeace/planet4-master-theme.

Please check the master theme code for more information.


---

# GPNL specific development instructions

# Prerequisites:
In order to benefit from this workflow you need to make sure you have the following tools installed.

- nodejs
- yarn
- grunt (globally)
- grunt-cli (globally)


# Fetch required vendor assets
In the root directory run the following command:
```
yarn
```

This command downloads all required assets and places them in the `node_modules` folder. You can view or edit the list of dependencies in the `package.json` file.

# Starting a development server
To start a watch daemon and spin up a development server run the following command:
```
grunt
```

While the development server is running all changes made to JS, CSS or HTML assets in the `src` directory will be optimized, copied over to the `.tmp` folder and refreshed in real-time.

From this point on it's just business as usual. Make your edits to the HTML, change some CSS or JS as you please.

# Building a project
If you're ready to deploy your project you need to create a build.
In the root directory run the following command:  
```
grunt build
```

This will leave you with a `dist` folder containing the optimized assets.
