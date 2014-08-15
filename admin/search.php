<?php 
/** Project Name: Nimbus (Circle K Report Form System)
 ** Search Queries (search.php)
 **
 ** Author: Jerry Bao (jbao@berkeley.edu)
 ** Author: Robert Rodriguez (rob.rodriguez@berkeley.edu)
 ** Author: Diyar Aniwar (diyaraniwar@berkeley.edu)
 ** 
 ** CIRCLE K INTERNATIONAL
 ** COPYRIGHT 2014-2015 - ALL RIGHTS RESERVED
 **/
ini_set('display_errors', 1);
require_once("dbfunc.php");
$userdb = new UserFunctions;

switch ($_POST['search_type']) {
    case "events":
        break;
    case "roster":
        $users = $userdb->searchUsers($_POST['search_words'], $_POST['search_category']);

        if ($users) {
            foreach ($users as $user) {
                $user['dues_paid'] = $user['dues_paid'] ? "Yes" : "No";
                $user['email_confirmed'] = $user['email_confirmed'] ? "Yes" : "No"; ?>

                <tr><td><a href="roster.php?view=member&id=<?= $user['user_id'] ?>"><?= $user['first_name'] ?> <?= $user['last_name'] ?></a></td>
                <td><?= $user['email'] ?></td>
                <td><?= $user['phone'] ?></td>
                <td><?= $user['dues_paid'] ?></td>
                <td><?= $user['email_confirmed'] ?></td></tr>
<?          }
        } else { ?>
            <tr><td>No Members Found.</td>
            <td>N/A</td>
            <td>N/A</td>
            <td>N/A</td>
            <td>N/A</td></tr>
<?      }
        break;
}

?>