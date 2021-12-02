<?php

$page_title = "Search Case results";

require_once ('includes/header.php');
require_once('includes/database.php');

if (filter_has_var(INPUT_GET, "terms")) {
    $terms_str = filter_input(INPUT_GET, 'terms', FILTER_SANITIZE_STRING);
} else {
    echo "There was not search terms found.";
    include ('includes/footer.php');
    exit;
}

//explode the search terms into an array
$terms = explode(" ", $terms_str);

//select statement using pattern search. Multiple terms are concatnated in the loop.
$sql = "SELECT * FROM products WHERE 1";
foreach ($terms as $term) {
    $sql .= " AND title LIKE '%$term%'";
}


//execute the query
$query = $conn->query($sql);

//Handle selection errors
if (!$query) {
    $errno = $conn->errno;
    $errmsg = $conn->error;
    echo "Selection failed with: ($errno) $errmsg.";
    $conn->close();
    include ('includes/footer.php');
    exit;
}

echo "<h2>products: $terms_str</h2>";

//display results in a table
if ($query->num_rows == 0)
    echo "Your search <i>'$terms_str'</i> did not match any case in our inventory";
else {
    ?>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col"> </th>
            <th scope="col">Product</th>
            <th scope="col">Description</th>
            <th scope="col">Available</th>
            <th scope="col" class="text-right">Price</th>
            <th> </th>
        </tr>
        </thead>

        <?php
        //insert a row into the table for each row of data
        while (($row = $query->fetch_assoc()) !== NULL) {
            echo "<tr>";
//            echo "<td><a href='productdetails.php.php?id=", $row['id'], "'>", $row['title'], "</a></td>";
            echo "<td>", $row['product_name'], "</td>";
            echo "<td>", $row['product_description'], "</td>";
            echo "<td>", $row['in_stock'], "</td>";
            echo "<td>", $row['product_price'], "</td>";
            echo "</tr>";



        }
        ?>
    </table>

    <?php
}
// clean up resultsets when we're done with them!
$query->close();

// close the connection.
$conn->close();

include ('includes/footer.php');
