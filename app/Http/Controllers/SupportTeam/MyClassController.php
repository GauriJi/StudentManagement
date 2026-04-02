<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Requests\MyClass\ClassCreate;
use App\Http\Requests\MyClass\ClassUpdate;
use App\Repositories\MyClassRepo;
use App\Repositories\UserRepo;
use App\Http\Controllers\Controller;

class MyClassController extends Controller
{
    protected $my_class, $user;

    public function __construct(MyClassRepo $my_class, UserRepo $user)
    {
        $this->middleware('teamSA', ['except' => ['destroy',] ]);
        $this->middleware('super_admin', ['only' => ['destroy',] ]);

        $this->my_class = $my_class;
        $this->user = $user;
    }

    public function index()
    {
        $d['my_classes'] = $this->my_class->all();
        $d['class_types'] = $this->my_class->getTypes();
        $d['teachers'] = $this->user->getUserByType('teacher');

        return view('pages.support_team.classes.index', $d);
    }

    public function store(ClassCreate $req)
    {
        $data = $req->all();
        
        // Ensure only Admin can assign teacher_id
        if (!Qs::userIsAdmin() && isset($data['teacher_id'])) {
            unset($data['teacher_id']);
        }
        
        $mc = $this->my_class->create($data);

        // Create Default Section
        $s =['my_class_id' => $mc->id,
            'name' => 'A',
            'active' => 1,
            'teacher_id' => NULL,
        ];

        $this->my_class->createSection($s);

        return Qs::jsonStoreOk();
    }

    public function edit($id)
    {
        $user = \Auth::user();
        \Log::info('MyClassController@edit: User ID: ' . $user->id . ' | Role: ' . $user->user_type . ' | Class ID: ' . $id);
        
        $d['c'] = $c = $this->my_class->find($id);
        $d['teachers'] = $this->user->getUserByType('teacher');

        return is_null($c) ? Qs::goWithDanger('classes.index') : view('pages.support_team.classes.edit', $d) ;
    }

    public function update(ClassUpdate $req, $id)
    {
        $data = $req->only(['name', 'teacher_id']);
        
        // Ensure only Admin can assign teacher_id
        if (!Qs::userIsAdmin() && isset($data['teacher_id'])) {
            unset($data['teacher_id']);
        }

        $this->my_class->update($id, $data);

        return Qs::jsonUpdateOk();
    }

    public function destroy($id)
    {
        $this->my_class->delete($id);
        return back()->with('flash_success', __('del_ok'));
    }

}
