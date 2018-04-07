<?php

$html .='</div>
</td></tr>
</table>
</div>

<div class="credit">

Powered by: <a target="_blank" href="http://foldergallery.jv2.net">JV2 Folder Gallery</a> Admin '.$admin_version;


if (isset($_SESSION['username'])) {
$html .= '&nbsp;&nbsp;<a href="?logout=1">Logout</a>';
}


$html .= '</div>

</body>
</html>';


?>



