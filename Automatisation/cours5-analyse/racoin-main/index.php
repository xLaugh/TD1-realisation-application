<?php
require 'vendor/autoload.php';

use db\connection;
use Illuminate\Database\Query\Expression as raw;
use model\Annonce;
use model\Categorie;
use model\Annonceur;
use model\Departement;
use Slim\Factory\AppFactory;
use Slim\Psr7\Response;
use Slim\Psr7\Request;

connection::createConn();

$app = AppFactory::create();

if (!isset($_SESSION)) {
    session_start();
    $_SESSION['formStarted'] = true;
}

if (!isset($_SESSION['token'])) {
    $token = md5(uniqid(rand(), true));
    $_SESSION['token'] = $token;
    $_SESSION['token_time'] = time();
} else {
    $token = $_SESSION['token'];
}

$loader = new \Twig\Loader\FilesystemLoader('template');
$twig = new \Twig\Environment($loader);

$menu = array(
    array('href' => "./index.php", 'text' => 'Accueil')
);

$chemin = dirname($_SERVER['SCRIPT_NAME']);
$cat = new \controller\getCategorie();
$dpt = new \controller\getDepartment();

// Helper pour capturer le output des contrôleurs et le mettre dans la réponse
$writeFromController = function ($response, $callback) use ($twig, $menu, $chemin, $cat, $dpt) {
    ob_start();
    $callback($twig, $menu, $chemin, $cat, $dpt);
    $response->getBody()->write(ob_get_clean());
    return $response;
};

$app->get('/', function (Request $request, Response $response) use ($twig, $menu, $chemin, $cat, $writeFromController) {
    $index = new \controller\index();
    return $writeFromController($response, function ($twig, $menu, $chemin, $cat) use ($index) {
        $index->displayAllAnnonce($twig, $menu, $chemin, $cat->getCategories());
    });
});

$app->get('/item/{n}', function (Request $request, Response $response, array $args) use ($twig, $menu, $chemin, $cat, $writeFromController) {
    $item = new \controller\item();
    return $writeFromController($response, function ($twig, $menu, $chemin) use ($item, $args) {
        $item->afficherItem($twig, $menu, $chemin, $args['n'], (new \controller\getCategorie())->getCategories());
    });
});

$app->get('/add/', function (Request $request, Response $response) use ($twig, $menu, $chemin, $cat, $dpt, $writeFromController) {
    $ajout = new \controller\addItem();
    return $writeFromController($response, function ($twig, $menu, $chemin, $cat) use ($ajout, $dpt) {
        $ajout->addItemView($twig, $menu, $chemin, $cat->getCategories(), $dpt->getAllDepartments());
    });
});

$app->post('/add/', function (Request $request, Response $response) use ($twig, $menu, $chemin, $writeFromController) {
    $allPostVars = $request->getParsedBody() ?? [];
    $ajout = new \controller\addItem();
    return $writeFromController($response, function ($twig, $menu, $chemin) use ($ajout, $allPostVars) {
        $ajout->addNewItem($twig, $menu, $chemin, $allPostVars);
    });
});

$app->get('/item/{id}/edit', function (Request $request, Response $response, array $args) use ($twig, $menu, $chemin, $writeFromController) {
    $item = new \controller\item();
    return $writeFromController($response, function ($twig, $menu, $chemin) use ($item, $args) {
        $item->modifyGet($twig, $menu, $chemin, $args['id']);
    });
});

$app->post('/item/{id}/edit', function (Request $request, Response $response, array $args) use ($twig, $menu, $chemin, $cat, $dpt, $writeFromController) {
    $allPostVars = $request->getParsedBody() ?? [];
    $item = new \controller\item();
    return $writeFromController($response, function ($twig, $menu, $chemin, $cat) use ($item, $args, $allPostVars, $dpt) {
        $item->modifyPost($twig, $menu, $chemin, $args['id'], $allPostVars, $cat->getCategories(), $dpt->getAllDepartments());
    });
});

$app->map(['GET', 'POST'], '/item/{id}/confirm', function (Request $request, Response $response, array $args) use ($twig, $menu, $chemin, $writeFromController) {
    $allPostVars = $request->getParsedBody() ?? [];
    $item = new \controller\item();
    return $writeFromController($response, function ($twig, $menu, $chemin) use ($item, $args, $allPostVars) {
        $item->edit($twig, $menu, $chemin, $allPostVars, $args['id']);
    });
});

$app->get('/search/', function (Request $request, Response $response) use ($twig, $menu, $chemin, $cat, $writeFromController) {
    $s = new \controller\Search();
    return $writeFromController($response, function ($twig, $menu, $chemin, $cat) use ($s) {
        $s->show($twig, $menu, $chemin, $cat->getCategories());
    });
});

$app->post('/search/', function (Request $request, Response $response) use ($twig, $menu, $chemin, $cat, $writeFromController) {
    $array = $request->getParsedBody() ?? [];
    $s = new \controller\Search();
    return $writeFromController($response, function ($twig, $menu, $chemin, $cat) use ($s, $array) {
        $s->research($array, $twig, $menu, $chemin, $cat->getCategories());
    });
});

$app->get('/annonceur/{n}', function (Request $request, Response $response, array $args) use ($twig, $menu, $chemin, $cat, $writeFromController) {
    $annonceur = new \controller\viewAnnonceur();
    return $writeFromController($response, function ($twig, $menu, $chemin, $cat) use ($annonceur, $args) {
        $annonceur->afficherAnnonceur($twig, $menu, $chemin, $args['n'], $cat->getCategories());
    });
});

$app->get('/del/{n}', function (Request $request, Response $response, array $args) use ($twig, $menu, $chemin, $writeFromController) {
    $item = new \controller\item();
    return $writeFromController($response, function ($twig, $menu, $chemin) use ($item, $args) {
        $item->supprimerItemGet($twig, $menu, $chemin, $args['n']);
    });
});

$app->post('/del/{n}', function (Request $request, Response $response, array $args) use ($twig, $menu, $chemin, $cat, $writeFromController) {
    $postData = $request->getParsedBody() ?? [];
    $item = new \controller\item();
    return $writeFromController($response, function ($twig, $menu, $chemin, $cat) use ($item, $args, $postData) {
        $item->supprimerItemPost($twig, $menu, $chemin, $args['n'], $cat->getCategories(), $postData);
    });
});

$app->get('/cat/{n}', function (Request $request, Response $response, array $args) use ($twig, $menu, $chemin, $cat, $writeFromController) {
    $categorie = new \controller\getCategorie();
    return $writeFromController($response, function ($twig, $menu, $chemin, $cat) use ($categorie, $args) {
        $categorie->displayCategorie($twig, $menu, $chemin, $cat->getCategories(), $args['n']);
    });
});

$app->get('/api', function (Request $request, Response $response) use ($twig, $menu, $chemin) {
    $template = $twig->load("api.html.twig");
    $menuApi = array(
        array('href' => $chemin, 'text' => 'Acceuil'),
        array('href' => $chemin . '/api', 'text' => 'Api')
    );
    $response->getBody()->write($template->render(array("breadcrumb" => $menuApi, "chemin" => $chemin)));
    return $response;
});

$app->group('/api', function (\Slim\Routing\RouteCollectorProxy $group) use ($twig, $menu, $chemin, $cat) {
    $group->group('/annonce', function (\Slim\Routing\RouteCollectorProxy $group) {
        $group->get('/{id}', function (Request $request, Response $response, array $args) {
            $annonceList = ['id_annonce', 'id_categorie as categorie', 'id_annonceur as annonceur', 'id_departement as departement', 'prix', 'date', 'titre', 'description', 'ville'];
            $return = Annonce::select($annonceList)->find($args['id']);

            if ($return !== null) {
                $return->categorie = Categorie::find($return->categorie);
                $return->annonceur = Annonceur::select('email', 'nom_annonceur', 'telephone')->find($return->annonceur);
                $return->departement = Departement::select('id_departement', 'nom_departement')->find($return->departement);
                $links = [];
                $links["self"]["href"] = "/api/annonce/" . $return->id_annonce;
                $return->links = $links;
                $response = $response->withHeader('Content-Type', 'application/json');
                $response->getBody()->write($return->toJson());
                return $response;
            }
            return $response->withStatus(404);
        });
    });

    $group->group('/annonces', function (\Slim\Routing\RouteCollectorProxy $group) {
        $group->get('/', function (Request $request, Response $response) {
            $annonceList = ['id_annonce', 'prix', 'titre', 'ville'];
            $a = Annonce::select($annonceList)->get();
            $links = [];
            foreach ($a as $ann) {
                $links["self"]["href"] = "/api/annonce/" . $ann->id_annonce;
                $ann->links = $links;
            }
            $links["self"]["href"] = "/api/annonces/";
            $a->links = $links;
            $response = $response->withHeader('Content-Type', 'application/json');
            $response->getBody()->write($a->toJson());
            return $response;
        });
    });

    $group->group('/categorie', function (\Slim\Routing\RouteCollectorProxy $group) {
        $group->get('/{id}', function (Request $request, Response $response, array $args) {
            $response = $response->withHeader('Content-Type', 'application/json');
            $a = Annonce::select('id_annonce', 'prix', 'titre', 'ville')
                ->where("id_categorie", "=", $args['id'])
                ->get();
            $links = [];
            foreach ($a as $ann) {
                $links["self"]["href"] = "/api/annonce/" . $ann->id_annonce;
                $ann->links = $links;
            }
            $c = Categorie::find($args['id']);
            if ($c === null) {
                return $response->withStatus(404);
            }
            $links["self"]["href"] = "/api/categorie/" . $args['id'];
            $c->links = $links;
            $c->annonces = $a;
            $response->getBody()->write($c->toJson());
            return $response;
        });
    });

    $group->group('/categories', function (\Slim\Routing\RouteCollectorProxy $group) {
        $group->get('/', function (Request $request, Response $response) {
            $response = $response->withHeader('Content-Type', 'application/json');
            $c = Categorie::get();
            $links = [];
            foreach ($c as $catItem) {
                $links["self"]["href"] = "/api/categorie/" . $catItem->id_categorie;
                $catItem->links = $links;
            }
            $links["self"]["href"] = "/api/categories/";
            $c->links = $links;
            $response->getBody()->write($c->toJson());
            return $response;
        });
    });

    $group->get('/key', function (Request $request, Response $response) use ($twig, $menu, $chemin, $cat) {
        $kg = new \controller\KeyGenerator();
        ob_start();
        $kg->show($twig, $menu, $chemin, $cat->getCategories());
        $response->getBody()->write(ob_get_clean());
        return $response;
    });

    $group->post('/key', function (Request $request, Response $response) use ($twig, $menu, $chemin, $cat) {
        $data = $request->getParsedBody() ?? [];
        $nom = $data['nom'] ?? '';
        $kg = new \controller\KeyGenerator();
        ob_start();
        $kg->generateKey($twig, $menu, $chemin, $cat->getCategories(), $nom);
        $response->getBody()->write(ob_get_clean());
        return $response;
    });
});

$app->run();
