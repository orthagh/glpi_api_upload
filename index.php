<!DOCTYPE html>
<html lang="fr">
<head>
   <meta charset="utf-8">
   <title>Example of fileupload with GLPI API</title>
   <!-- meta -->
   <meta name="author" content="Alexandre DELAUNAY">
   <style>
   body {
      max-width: 400px;
      margin: 0 auto;
   }

   label,
   input[type=submit] {
      display: block;
      font-weight: bold;
      margin-top: 1.5em;
   }

   input[type=text] {
      width: 90%;
   }

   </style>
</head>
<body>
   <header>
   <h1>Test form for upload documents to glpi API</h1>
   </header>
   <form action="upload.php" method="POST"  enctype="multipart/form-data">
      <label for="docname">Document's name:</label>
      <input type="text" name="document_name" id="docname" value="My document uploaded by api">

      <label for="file">File:</label>
      <input type="file" name="filename[]" id="file">

      <input type="submit" name="submit">
   </form>
</body>