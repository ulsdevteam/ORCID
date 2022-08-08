<!DOCTYPE html>

<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="UTF-8" />
  <title>ORCID @ Pitt</title>
  <link href="../../webroot/css/default.css" rel="stylesheet" type="text/css" />
</head>

<body>
  <div id="pitt-header" class="blue">
    <div id="pittlogo"><a id="p-link" title="University of Pittsburgh" href="http://pitt.edu/">University of
        Pittsburgh</a></div>
  </div>
  <div id="wrapper">
    <header>
      <h1><img src="../../webroot/img/header.jpg" alt="ORCID @ Pitt"></h1>
    </header>
    <?= $this->fetch('content') ?>
    <footer>
      <div class="foot-col">
        <h2>What is ORCID?</h2>
        <p class="descr">ORCID provides a unique, persistent identifier that can help make your scholarship
          easier to find and attribute.</p>
        <p class="linker"><a class="actionbutton" href="https://orcid.org/">Learn more about ORCID</a></p>
      </div>
      <div class="foot-col">
        <h2>ORCID@Pitt</h2>
        <p class="descr">Find out more about the benefits of an ORCID iD and the university’s effort to
          encourage Pitt researchers to create an ORCID iD, use it with their scholarship, and connect their
          ORCID iD with Pitt.</p>
        <p class="linker"><a class="actionbutton" href="http://www.library.pitt.edu/orcid">Discover
            ORCID@Pitt</a></p>
      </div>
      <div class="foot-col">
        <h2>Get Help.</h2>
        <p class="descr">If you need help with creating your ORCID iD or have further questions, please contact
          us.</p>
        <p class="linker"><a class="actionbutton" href="mailto:orcidcomm@mail.pitt.edu">orcidcomm@mail.pitt.edu</a></p>
      </div>
    </footer>
  </div><!-- /end wrapper -->
</body>

</html>