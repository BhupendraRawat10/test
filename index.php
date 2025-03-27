<?php
// test the Pokémon info code 
function fetchPokemonData($pokemon)
{
    $url = "https://pokeapi.co/api/v2/pokemon/" . strtolower($pokemon);
    
    $response = @file_get_contents($url);
    

// dd($response);

    return json_decode($response, true);
}

$pokemon = isset($_GET['pokemon']) ? trim($_GET['pokemon']) : 'pikachu';

$data = fetchPokemonData($pokemon);
// dd($data);
if (!$data) {
    $error = "Pokémon not found! Try again.";
} else {

    $name = ucfirst($data['name']); 
    $id = $data['id'];
    $image = $data['sprites']['front_default'];
    $types = [];
    foreach ($data['types'] as $type) {
        $types[] = ucfirst($type['type']['name']); 
    }

    $stats = [];
    foreach ($data['stats'] as $stat) {
        $stats[$stat['stat']['name']] = $stat['base_stat'];
    }
//    dd($stats);
    $prevId = max(1, $id - 1); 
    $nextId = $id + 1;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokémon Info App</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; background-color: #f8f8f8; }
        .container { width: 50%; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); }
        img { width: 150px; height: 150px; }
        .stats { text-align: left; display: inline-block; margin-top: 10px; }
        .search-box { margin-bottom: 20px; }
        input { padding: 8px; width: 200px; border-radius: 5px; border: 1px solid #ccc; }
        button { padding: 8px 12px; border: none; background:rgb(130, 204, 233); color: black; cursor: pointer; border-radius: 5px; }
        .nav { margin-top: 20px; }
        .nav a { text-decoration: none; padding: 8px 12px; background:rgb(146, 175, 206); color: white; border-radius: 5px; margin: 5px; }
        .error { color: red; font-weight: bold; }
    </style>
</head>
<body>

    <div class="container">
        <h1>Pokémon Info App</h1>

        <form method="GET" class="search-box">
            <input type="text" name="pokemon" placeholder="Enter Pokémon Name or ID" required>
            <button type="submit">Search</button>
        </form>

        <?php if (isset($error)) : ?>
            <p class="error"><?php echo $error; ?></p>
        <?php else : ?>
            <h2><?php echo $name; ?> (#<?php echo $id; ?>)</h2>
            <img src="<?php echo $image; ?>" alt="<?php echo $name; ?>">
            <p><strong>Types:</strong> <?php echo implode(", ", $types); ?></p>

            <div class="stats">
                <h3>Stats</h3>
                <ul>
                    <?php foreach ($stats as $key => $value) : ?>
                        <li><strong><?php echo ucfirst($key); ?>:</strong> <?php echo $value; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="nav">
                <a href="?pokemon=<?php echo $prevId; ?>">⬅ Previous</a>
                <a href="?pokemon=<?php echo $nextId; ?>">Next ➡</a>
            </div>
        <?php endif; ?>

    </div>

</body>
</html>
