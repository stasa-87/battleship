# battleship game

da opravq kontrolerite - da se dostupvat samo po post,get,ajax!!!
battleship logic!!!
validations!!!

UI!!!
assets!!!

da zachistq stariq kod - rutove, modeli, templeiti eventualno assets, @author komentari ot ide-to

coding standarts - https://symfony.com/doc/current/contributing/code/standards.html

ARRAY OF OBJECTS
/**
 * @param User[] $users
 */
function deleteUsers(array $users);
With variadic arguments we can rewrite it to

function deleteUsers(User ...$users);

FILTER ARRAY OF OBJECTS
function getNames(array $users, $excludeId)
{
    $filtered = array_filter($users, function ($u) use ($excludeId) {
        return $u['id'] != $excludeId;
    });

    return array_map(function ($u) { return $u['name']; }, $filtered);
}

REDUCE
function getNames(array $users, $excludeId)
{
    return array_reduce($users, function ($acc, $u) use ($excludeId) {
        if ($u['id'] == $excludeId) {
            return $acc;
        }

        return array_merge($acc, [ $u['name'] ]);
    }, []);
}