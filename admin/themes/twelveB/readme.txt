12B Pixie Theme

* Author:	   Ryan McDonough
* URL:           www.ryanmcdonough.co.uk

----------------------------------------------------------------

To install this theme upload it to the admin/themes/ folder
within Pixie. 

Please read to modify your theme
----------------------------------------------------------------

/Logo/
The logo is done entirely by using cufon javascript, which 
generates the logo using the Sansation font.

Other fonts could be used by generating them at:

http://cufon.shoqolate.com/generate/

and then uploading the font to the js folder and editing line 85
in the theme.php file

<script type="text/javascript" src="admin/themes/<?php print $site_theme; ?>/js/sansation_400.font.js"></script>

changing the sansation_400.font.js line to the file you uploaded.

/Fonts/

The h4 headers (Blocks and post titles) also use Cufon to replace
the usual text with the Sansation font.

----------------------------------------------------------------
