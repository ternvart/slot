<?php if (isset($_SESSION["logged"]) === true) { ?>
    <div class="panel-header panel-header-sm"></div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="title">Contents</h5>
                        <p class="category"><a href="?view=content-create">Create a new content</a></p>
                    </div>
                    <div class="card-body">
                        <?php
                        $config = json_decode(option(), true);
                        $contents_list = database::list_contents();
                        ?>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Content Title</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($contents_list as $content) {
                                echo "<tr>";
                                echo "<td>" . $content["content_title"] . "</td>";
                                echo '<td>';
                                echo '<a target="_blank" class="btn btn-sm btn-info" href="'. $config["url"] .'/'. $content["content_slug"] . '"><i class="fas fa-link"></i></a>';
                                echo ' <a class="btn btn-sm btn-primary" href="?view=content-edit&id=' . $content["ID"] . '"><i class="fas fa-pencil-alt"></i></a>';
                                echo ' <a class="btn btn-sm btn-danger" href="?view=content-delete&id=' . $content["ID"] . '"><i class="fas fa-trash-alt"></i></a>';
                                echo '</td>';
                                echo "</tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else {
    http_response_code(403);
} ?>