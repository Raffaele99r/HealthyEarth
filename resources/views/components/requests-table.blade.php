<div class="container">
  <div class="row">
    <div class="table-responsive">
      <table class="bg-dark opacity table btnMobile table-striped table-custom table border text-white">
        <thead class="table-success">
          <tr>
            <th  scope="col">#</th>
            <th  scope="col">Nome</th>
            <th  scope="col">Email</th>
            <th  scope="col">Azioni</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($roleRequests as $user)
            <tr>
                <th class="text-white" scope="row">{{$user->id}}</th>
                <td class="text-white">{{$user->name}}</td>
                <td class="text-white">{{$user->email}}</td>
               
                <td>
    
                  @switch($role)
                      @case('amministratore')
                          <a href="{{route('admin.setAdmin', compact('user'))}}" class="btn btnMobile back rounded-0 text-white">Attiva {{$role}}</a>
                          @break
                      @case('revisore')
                          <a href="{{route('admin.setRevisor', compact('user'))}}" class="btn btnMobile back rounded-0 text-white">Attiva {{$role}}</a>
                          @break
                      @case('redattore')
                          <a href="{{route('admin.setWriter', compact('user'))}}" class="btn btnMobile back rounded-0 text-white">Attiva {{$role}}</a>
                          @break
                  @endswitch
                  
                </td>
              </tr>
            @endforeach
        </tbody>
      </table>
              
    </div>
  </div>
</div>
      