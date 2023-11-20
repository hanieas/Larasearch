# LaraSearch
This is a search package built to easily integrate with Laravel and the database.

# Installation
```bash
composer require hasemi/larasearch
```

# Usage

## Prepare Model

In order to search through models you'll have to let them implement the Searchable interface.

```php
namespace LaraSearch;

interface Searchable
{
    /**
     * @return array
     */
    public function getColumnsForExactMatch(): array;

    /**
     * @return array
     */
    public function getColumnsForLikeMatch(): array;

    /**
     * @return array
     */
    public function getColumnsForBooleanMatch(): array;

    /**
     * @return array
     */
    public function getColumnsForPeriodMatch(): array;
}
```
You need to add the below 4 functions in your model to define type of search for columns of your table. This package will search items that are in the fillable attribute of the model.
```php
use LaraSearch\Searchable;

class User extends Model implements Searchable
{
    protected $fillable = [
        'username',
        'mobile',
        'is_active',
        'logged_in',
    ];
     
    /**
     * @return array
     */
    public function getColumnsForExactMatch(): array {
        return [  
            'mobile'
        ];
    }

    /**
     * @return array
     */
    public function getColumnsForLikeMatch(): array {
         return [  
            'username'
        ];
    }

    /**
     * @return array
     */
    public function getColumnsForBooleanMatch(): array {
         return [  
            'is_active'
        ];
    }

    /**
     * @return array
     */
    public function getColumnsForPeriodMatch(): array
    {
        return [  
            'logged_in'
        ];
    }
}
```
## Search

With the models prepared you can search them like this:
```php
class UserController extends Controller
{

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $users = new Search(Request()->toArray(), User::query())->get();
        return response()->json([
            'users' => $users
        ]);
    }
}
```
You can easily search from the users' list by calling the below link:
```
{{address}}/users?username=hanie?mobile=?is_active=1?logged_in_from=2023-10-10?logged_in_to=2023-12-10
```
