# lmeve-ci

This is a refactoring of the lmeve application by Lukas Rox (https://github.com/roxlukas/lmeve) which will push it
into Code Igniter and hopefully bake in some more features :D.

# Things done so far

## LMeve_Controller
Inside application/core/LMeve_Controller.php built to handle authentication.  This Controller is meant
to be the base controller for all LMeve based controllers that require authentication before moving
forward.  It will also have functionality for 'drawing' the menu and the side bar.

## Template
Inside the libraries/template is a very basic templating engine captured from: http://code.tutsplus.com/tutorials/an-introduction-to-views-templating-in-codeigniter--net-25648
which will load the layout and then the 'view' that is embedded inside.

## iveeCore
Put in place, but not 'activated' as of yet as I'm not sure how it'll behave alongside CI just yet... we'll cross that
bridge later...

## Timesheet view
The Timesheet 'landing page' has been fully refactored minus editing of the points on that page.

## Queue view
This view is meant as a higher level view into the work that the corporation wants to perform in the given month.  Ideally it will work with or without
tasks assigned to individuals and will just take a aggregated view of all the work done on that given overall task.  Some functionality currently doesn't
exist as the database section hasn't been migrated over but once that is in then creating queue's will be fully implemented.