<?php

namespace App\DataTables;

use App\Models\Tag;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', 'users.action');
    }

    /*  public function ajax()
      {
             $data = User::select('*');
          return Datatables::of($data)
              ->addIndexColumn()
              ->addColumn('action', function($row){

                  $btn = '<a href="javascript:void(0)" class="edit btn btn-info btn-sm ml-2">View</a>';
                  $btn = $btn.'<a href="/users/'. $row->id .'/edit"   class="edit btn btn-primary btn-sm ml-2">Edit</a>';
                //  $btn = $btn.'<a href="javascript:void(0)" class="edit btn btn-danger btn-sm">Delete</a>';
                $btn=$btn. '<a data-toggle="modal" data-target="#delete' . $row->id  .'" class="edit btn btn-danger btn-sm ml-2">Delete</a>

  <button class="update" data-toggle="modal" data-target="#modal-update" style="background:none;border:none;padding:0%" title="modifier"
  data-id="{{$patient -> id}}" data-name="{{$patient -> name}}" data-surname="{{$patient -> surname}}" data-birthdate="{{$patient -> birthdate}}" data-gender="{{$patient -> gender}}" data-adress="{{$patient -> adress}}" data-number="{{$patient -> number}}">
                          <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-left:10px">
                              <path d="M18.3134 0.797364C17.5214 -0.0131839 16.2305 -0.0131839 15.4385 0.797364L15.022 1.21606L18.6888 4.96283L19.0334 4.60855C19.4345 4.20059 19.6557 3.65844 19.6557 3.07334C19.6557 2.48824 19.4345 1.94609 19.0385 1.53813L18.3134 0.797364Z" fill="#7D90B2"/>
                              <path d="M13.1812 3.08965L16.848 6.8364L17.9537 5.71453L14.2869 1.96777L13.1812 3.08965Z" fill="#7D90B2"/>
                              <path d="M1.73828 14.743L0 20.2772L5.4154 18.4736L16.1125 7.58759L12.4456 3.84082L1.73828 14.743Z" fill="#7D90B2"/>
                          </svg>
                      </button>

                      <div class="modal fade" id="modal-update">
                          <div class="modal-dialog modal-lg">
                              <div class="modal-content" >
                                  <div class="modal-header" style="width:100%">
                                      <h4 style="margin-left:15%; text-align: center; color:#555555">Modifier les informations personnelles de patient </h4>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                      </button>
                                  </div>
                                  <div class="card-body">
                                      <div style="width: 98%; margin-left:1%; margin-top:1%">
                                              <form method="POST" action="{{route(\'updatePatient\')}}" id="formUpdate" class="form-g">
                                                  @csrf
                                                  <input hidden name="id" id="idUpdate">

                                                  <div class="form-c row">
                                                      <label for="name" class="col-sm-4 col-form-label" style="color:#555555" >Nom</label>
                                                      <div class="col-sm-8">
                                                          <div>
                                                              <input type="text" class="form-control" name="name" id="nameUpdate" placeholder="Nom du patient">
                                                              <i class="fas fa-check-circle"></i>
                                                              <i class="fas fa-exclamation-circle"></i>
                                                              <small>Error message</small>
                                                          </div>
                                                      </div>
                                                  </div>


                                                  <div class="form-c row">
                                                      <label for="gender" class="col-sm-4 col-form-label" style="color:#555555">Sexe</label>
                                                      <div class="col-sm-8">
                                                          <div>
                                                              <select class="form-control" name="gender" id="genderUpdate" >
                                                                  <option value="Homme">Homme</option>
                                                                  <option value="Femme">Femme</option>
                                                              </select>
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <div class="form-c row">
                                                      <label for="adress" class="col-sm-4 col-form-label" style="color:#555555">Adresse</label>
                                                      <div class="col-sm-8">
                                                          <div>
                                                              <input type="text" class="form-control" id="adressUpdate" name="adress"  placeholder="Adresse du patient">
                                                              <i class="fas fa-check-circle"></i>
                                                              <i class="fas fa-exclamation-circle"></i>
                                                              <small>Error message</small>
                                                          </div>
                                                      </div>
                                                  </div>

                                                      <button type="submit" class="btn btn-block bg-gradient-primary" style="margin-left: 40%;margin-top:20px ; width: 120px;color: white">Enregistrer</button>
                                              </form>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>




                      const formUpdate = document.getElementById(\'formUpdate\');
      const idUpdate = document.getElementById(\'idUpdate\');
      const nameUpdate = document.getElementById(\'nameUpdate\');
      const surnameUpdate = document.getElementById(\'surnameUpdate\');
      const birthdateUpdate = document.getElementById(\'birthdateUpdate\');
      const genderUpdate = document.getElementById(\'genderUpdate\');
      const adressUpdate = document.getElementById(\'adressUpdate\');
      const numberUpdate = document.getElementById(\'numberUpdate\');
      var today = new Date();
      var dd = today.getDate();
      var mm = today.getMonth()+1; //January is 0!
      var yyyy = today.getFullYear();
      if(dd<10){
              dd=\'0\'+dd
          }
          if(mm<10){
              mm=\'0\'+mm
      }

          today = yyyy+\'-\'+mm+\'-\'+dd;
          document.getElementById("birthdateUpdate").setAttribute("max", today);

      const update = document.getElementsByClassName(\'update\');
      const array  = Array.from(update)

      // console.log(typeof update)
      array.forEach(element => {
              element.addEventListener(\'click\', event => {
                  const idValue = element.dataset.id;
                  const nameValue = element.dataset.name;
                  const surnameValue = element.dataset.surname;
                  const birthdateValue = element.dataset.birthdate;
                  const genderValue = element.dataset.gender;
                  const adressValue = element.dataset.adress;
                  const numberValue = element.dataset.number;
                  console.log(idValue)
                  idUpdate.value=idValue;
                  nameUpdate.value=nameValue;
                  surnameUpdate.value=surnameValue;
                  birthdateUpdate.value=birthdateValue;
                  genderUpdate.value=genderValue;
                  adressUpdate.value=adressValue;
                  numberUpdate.value=numberValue;
              })

          })

          formUpdate.addEventListener(\'submit\', e => {
          e.preventDefault();

          checkInputsUpdate();
      });


      function checkInputsUpdate() {
          const nameValue = nameUpdate.value.trim();
          const surnameValue = surnameUpdate.value.trim();
          const birthdateValue = birthdateUpdate.value.trim();
          const genderValue = genderUpdate.value.trim();
          const adressValue = adressUpdate.value.trim();
          const numberValue = numberUpdate.value.trim();
          var x=0;

          array.forEach(element => {
              if(idUpdate.value == element.dataset.id){
                  if(nameValue == element.dataset.name && surnameValue == element.dataset.surname && birthdateValue == element.dataset.birthdate && genderValue == element.dataset.gender && adressValue == element.dataset.adress && numberValue == element.dataset.number) {
                      return x= x-1;
                  }
              }
          })

          if(nameValue.length < 3) {
              setErrorFor(nameUpdate, "Le nom doit contenir au moins 3 caractères");
          } else if(formatSpecial.test(nameValue)){
              setErrorFor(nameUpdate, \'Le nom ne doit pas contenir des characters spécial\');
          } else if(formatNumber.test(nameValue)){
              setErrorFor(nameUpdate, \'Le nom ne doit pas contenir des chiffres\');
          } else {
              setSuccessFor(nameUpdate);
              x= x+1;
          }

          if(surnameValue.length < 3) {
              setErrorFor(surnameUpdate, "Le prénom doit contenir au moins 3 caractères");
          } else if(formatSpecial.test(surnameValue)){
              setErrorFor(surnameAdd, \'Le prénom ne doit pas contenir des characters spécial\');
          } else if(formatNumber.test(surnameValue)){
              setErrorFor(surnameUpdate, \'Le prénom ne doit pas contenir des chiffres\');
          } else {
              setSuccessFor(surnameUpdate);
              x= x+1;
          }


          if(birthdateValue === \'\') {
              setErrorFor(birthdateUpdate, \'Date de naissance ne peut pas être vide\');
          } else {
              setSuccessFor(birthdateUpdate);
              x= x+1;
          }
          if(numberValue.length < 10) {
              setErrorFor(numberUpdate, "La longueur du numéro doit être 10");
          } else if(formatSpecial.test(numberValue)){
              setErrorFor(numberUpdate, \'Le numéro ne doit pas contenir des characters spécial\');
          }else {
              setSuccessFor(numberUpdate);
              x= x+1;
          }
          if(x == 4){
              formUpdate.submit();
          }

      }

                              <!-- modal -->
                                  <div class="modal fade" style="margin-top: 200px" id="delete' . $row->id.'" >
                                      <div class="modal-dialog">
                                          <div class="modal-content">
                                          <div class="modal-header">
                                              <h4 class="modal-title"><i class="fas fa-trash text-danger"></i> Delete User</h4>
                                              <button type="button" class="close border-danger" data-dismiss="modal" aria-label="Close">×</button>
                                          </div>
                                          <div class="modal-body">

                                          <p>Are you sure you want to delete this User</p>
                                          </div>
                                          <div class="modal-footer justify-content-between">
                                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                          <a href="'.route('user/delete',$row->id). '" class="btn btn-danger">Delete</a>
                                          </div>
                                      </div>
                                  </div>
  ';


                  return $btn;
              })
              ->rawColumns(['action'])
              ->make(true);
      }*/
    public function ajax()
    {
        $data = User::select('*');
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                $btn = '<a href="javascript:void(0)" class="edit btn btn-info btn-sm ml-2">View</a>';
                $btn = $btn . '<a href="/users/' . $row->id . '/edit"   class="edit btn btn-primary btn-sm ml-2">Edit</a>';
                //  $btn = $btn.'<a href="javascript:void(0)" class="edit btn btn-danger btn-sm">Delete</a>';
                $btn = $btn . '<button data-toggle="modal" data-target="#modal-delete" class="delete btn btn-danger btn-sm ml-2" data-id="' . $row->id . '">Delete</button>
';


                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    /**
     * Get query source of dataTable.
     *
     * @param \App\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('users-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(1)->buttons(
                Button::make('create'),
                Button::make('export'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [

            Column::make('id'),
            Column::make('username'),
            Column::make('email'),
            Column::make('role'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(260)
                ->addClass('text-center'),

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Users_' . date('YmdHis');
    }
}
