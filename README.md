==lmeve-ci

This is a refactoring of the lmeve application by Lukas Rox (https://github.com/roxlukas/lmeve) which will push it
into Code Igniter and hopefully bake in some more features :D.

==Things done so far

===LMeve_Controller
Inside application/core/LMeve_Controller.php built to handle authentication.  This Controller is meant
to be the base controller for all LMeve based controllers that require authentication before moving
forward.  It will also have functionality for 'drawing' the menu and the side bar.

===Template
Inside the libraries/template is a very basic templating engine captured from: http://code.tutsplus.com/tutorials/an-introduction-to-views-templating-in-codeigniter--net-25648
which will load the layout and then the 'view' that is embedded inside.

===iveeCore
Put in place, but not 'activated' as of yet as I'm not sure how it'll behave alongside CI just yet... we'll cross that
bridge later...

===Timesheet view
The Timesheet 'landing page' has been fully refactored minus editing of the points on that page.