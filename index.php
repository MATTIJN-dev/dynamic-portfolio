<?php
// Config
$jsonFile = "projects.json";

// Als projects.json niet bestaat → maak aan
if (!file_exists($jsonFile)) {
    file_put_contents($jsonFile, json_encode([], JSON_PRETTY_PRINT));
}

// Project toevoegen
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $projects = json_decode(file_get_contents($jsonFile), true);

    $newProject = [
        "id" => uniqid("project_"),
        "title" => htmlspecialchars($_POST["title"]),
        "short_description" => htmlspecialchars($_POST["short_description"]),
        "description" => htmlspecialchars($_POST["description"]),
        "tags" => array_map("trim", explode(",", $_POST["tags"])),
        "thumbnail" => htmlspecialchars($_POST["thumbnail"]),
        "link" => htmlspecialchars($_POST["link"]),
        "created_at" => date("Y-m-d H:i:s")
    ];

    $projects[] = $newProject;

    file_put_contents($jsonFile, json_encode($projects, JSON_PRETTY_PRINT));
    header("Location: index.php");
    exit;
}

// Projecten laden
$projects = json_decode(file_get_contents($jsonFile), true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ProjectHub Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #0f172a;
            color: #e5e7eb;
            padding: 30px;
        }
        h1 { margin-bottom: 10px; }
        form {
            background: #020617;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 40px;
        }
        input, textarea, button {
            width: 100%;
            margin-bottom: 12px;
            padding: 10px;
            border-radius: 8px;
            border: none;
        }
        button {
            background: #38bdf8;
            cursor: pointer;
            font-weight: bold;
        }
        .project {
            background: #020617;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        .tags {
            color: #38bdf8;
            font-size: 0.9em;
        }
    </style>
</head>
<body>

<h1>🚀 ProjectHub</h1>
<p>Add projects → automatically saved to <code>projects.json</code></p>

<form method="POST">
    <input type="text" name="title" placeholder="Project title" required>
    <input type="text" name="short_description" placeholder="Short description" required>
    <textarea name="description" placeholder="Full description" required></textarea>
    <input type="text" name="tags" placeholder="Tags (comma separated)">
    <input type="text" name="thumbnail" placeholder="Thumbnail URL or path">
    <input type="text" name="link" placeholder="Project link (GitHub / Download / Live)">
    <button type="submit">Add Project</button>
</form>

<h2>📦 Existing Projects</h2>

<?php if (empty($projects)): ?>
    <p>No projects yet.</p>
<?php endif; ?>

<?php foreach ($projects as $project): ?>
    <div class="project">
        <strong><?= $project["title"] ?></strong><br>
        <?= $project["short_description"] ?><br>
        <div class="tags">
            <?= implode(", ", $project["tags"]) ?>
        </div>
    </div>
<?php endforeach; ?>

</body>
</html>
