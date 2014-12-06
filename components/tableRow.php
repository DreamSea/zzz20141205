
        <tr<?php if (isset($_SESSION['device'])) { if ($result["name"] == $_SESSION['device']) {echo(" class=\"success\"");}} ?>>
          <td><?php echo($i+1);?></td>
          <td><?php echo($result["name"]);?></td>
          <td><?php echo($result["location"]);?></td>
        </tr>