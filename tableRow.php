
        <tr>
          <td><?php echo($i+1);?></td>
          <td><?php echo($result["name"]);?></td>
          <td><?php
						$location = $result["location"];
						if ($location == 0)
						{
							echo("Living Room");
						}
						else if ($location == 1)
						{
							echo("Thoroughly Lost");
						}
					?></td>
        </tr>