The middleware keeps the urls of a CakePHP website consistent by preventing, for example, 
an action in a given controller from being accessed in a way other than as defined in the routes.
The action `index` in the `UsersController`, for example, can be accessed via two different urls: (1) /users or or (/users/index) the middleware prevents this by making sure that the correct url (in this case /users) is the one used.

#Installation
Step 1) Download the UrlConsistenceMiddleware.php and move it to /src/Middleware/UrlConsistenceMiddleware.php
Step 2) Open your /src/Application.php and add the UrlConsistenceMiddleware to your middleware queeue but make sure that it is teh one immediately after the RoutingMiddleware.
Step 3) Optional - clear the cache.  You can do this via cmd: browse to your app directory and run this command: bin/cake cache clear_all
