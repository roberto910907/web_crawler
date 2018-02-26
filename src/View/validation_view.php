<link rel="stylesheet" href="/assets/css/style.css">

<?php

use App\Model\FilterModel;

echo '<div class="container">';

include 'components/filter_form.php';

echo '<table class="responsive-table">
        <thead>
        <tr>
          <th scope="col" style="text-align: center">Title</th>
          <th scope="col">Order</th>
          <th scope="col">Ammount of Comments</th>
          <th scope="col">Points</th>
        </tr>
      </thead>
      <tbody>';

$filteredNews = $this->session->get(FilterModel::ROWS);

foreach ($filteredNews as $new) {
    echo "<tr>";
    echo "<td><b>" . $new['title'] . "</b></td>";
    echo "<td>" . $new['number'] . "</td>";
    echo "<td>" . $new['comments'] . "</td>";
    echo "<td>" . $new['points'] . "</td>";
    echo "</tr>";
}

echo '</tbody>
  </table>
</div>';
