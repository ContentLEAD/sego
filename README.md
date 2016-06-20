To set up local instance:

- Get untracked files from tech server.

- Run MongoDB

- Run PHP and MySQL

- URL paths assume root installation (e.g. sego.dev). So if you're hosting the site on a directory, e.g. localhost/sego, you'll need to route this to a root domain (e.g. sego.dev).

- Go to http://sego.dev/index.php/gate/override to create a new user or login. Use your salesforce email.

- To view any social media data, you'll need to have your salesforce account be assigned to some clients in salesforce.

Key changes from base SEGO:

app/controller/ajax.php
app/controller/board.php
app/controller/sego.php

app/view/board.php
app/view/sego
