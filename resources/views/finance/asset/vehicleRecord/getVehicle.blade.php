<form action="/finance/asset/vehicleRecord/storeVehicle?idS={{ $id }}" method="post" role="form" enctype="multipart/form-data">
  @csrf
  @method('POST')
  <div class="modal-header">
      <div class="">
          <button class="close waves-effect waves-light btn btn-danger btn-sm pull-right" data-dismiss="modal">
              &times;
          </button>
      </div>
  </div>
  <div class="modal-body">
    <div class="row col-md-12">
      <div>
        <div class="form-group">
            <label class="form-label" for="type">Car Type</label>
            <select class="form-select" id="type" name="type">
            <option value="-" selected disabled>-</option>
            <option value="Sedan" {{ ($data['car']->type == "Sedan" ? 'selected' : '') }}>Sedan</option>
            <option value="SUV" {{ ($data['car']->type == "SUV" ? 'selected' : '') }}>SUV</option>
            <option value="Hatchback" {{ ($data['car']->type == "Hatchback" ? 'selected' : '') }}>Hatchback</option>
            <option value="Coupe" {{ ($data['car']->type == "Coupe" ? 'selected' : '') }}>Coupe</option>
            <option value="Convertible" {{ ($data['car']->type == "Convertible" ? 'selected' : '') }}>Convertible</option>
            <option value="Wagon" {{ ($data['car']->type == "Wagon" ? 'selected' : '') }}>Wagon</option>
            <option value="Pickup Truck" {{ ($data['car']->type == "Pickup Truck" ? 'selected' : '') }}>Pickup Truck</option>
            <option value="Crossover" {{ ($data['car']->type == "Crossover" ? 'selected' : '') }}>Crossover</option>
            <option value="Luxury Car" {{ ($data['car']->type == "Luxury Car" ? 'selected' : '') }}>Luxury Car</option>
            <option value="Sports Car" {{ ($data['car']->type == "Sports Car" ? 'selected' : '') }}>Sports Car</option>
            <option value="Diesel Car" {{ ($data['car']->type == "Diesel Car" ? 'selected' : '') }}>Diesel Car</option>
            <option value="Electric Car" {{ ($data['car']->type == "Electric Car" ? 'selected' : '') }}>Electric Car</option>
            <option value="Hybrid Car" {{ ($data['car']->type == "Hybrid Car" ? 'selected' : '') }}>Hybrid Car</option>
            <option value="Off-Road Vehicle" {{ ($data['car']->type == "Off-Road Vehicle" ? 'selected' : '') }}>Off-Road Vehicle</option>
            <option value="Microcar" {{ ($data['car']->type == "Microcar" ? 'selected' : '') }}>Microcar</option>
            <option value="Roadster" {{ ($data['car']->type == "Roadster" ? 'selected' : '') }}>Roadster</option>
            <option value="Limousine" {{ ($data['car']->type == "Limousine" ? 'selected' : '') }}>Limousine</option>
            <option value="Muscle Car" {{ ($data['car']->type == "Muscle Car" ? 'selected' : '') }}>Muscle Car</option>
            <option value="Compact Car" {{ ($data['car']->type == "Compact Car" ? 'selected' : '') }}>Compact Car</option>
            <option value="Subcompact Car" {{ ($data['car']->type == "Subcompact Car" ? 'selected' : '') }}>Subcompact Car</option>
            <option value="MPV" {{ ($data['car']->type == "MPV" ? 'selected' : '') }}>MPV</option>
            </select>
        </div>
      </div>
      <div>
        <div class="form-group">
            <label class="form-label" for="brand">Car Brand</label>
            <select class="form-select" id="brand" name="brand">
            <option value="-" selected disabled>-</option>
            <option value="Proton" {{ ($data['car']->brand == "Proton" ? 'selected' : '') }}>Proton</option>
            <option value="Perodua" {{ ($data['car']->brand == "Perodua" ? 'selected' : '') }}>Perodua</option>
            <option value="Honda" {{ ($data['car']->brand == "Honda" ? 'selected' : '') }}>Honda</option>
            <option value="Toyota" {{ ($data['car']->brand == "Toyota" ? 'selected' : '') }}>Toyota</option>
            <option value="Nissan" {{ ($data['car']->brand == "Nissan" ? 'selected' : '') }}>Nissan</option>
            <option value="Mazda" {{ ($data['car']->brand == "Mazda" ? 'selected' : '') }}>Mazda</option>
            <option value="Mitsubishi" {{ ($data['car']->brand == "Mitsubishi" ? 'selected' : '') }}>Mitsubishi</option>
            <option value="Ford" {{ ($data['car']->brand == "Ford" ? 'selected' : '') }}>Ford</option>
            <option value="Hyundai" {{ ($data['car']->brand == "Hyundai" ? 'selected' : '') }}>Hyundai</option>
            <option value="Kia" {{ ($data['car']->brand == "Kia" ? 'selected' : '') }}>Kia</option>
            <option value="BMW" {{ ($data['car']->brand == "BMW" ? 'selected' : '') }}>BMW</option>
            <option value="Mercedes-Benz" {{ ($data['car']->brand == "Mercedes-Benz" ? 'selected' : '') }}>Mercedes-Benz</option>
            <option value="Audi" {{ ($data['car']->brand == "Audi" ? 'selected' : '') }}>Audi</option>
            <option value="Volkswagen" {{ ($data['car']->brand == "Volkswagen" ? 'selected' : '') }}>Volkswagen</option>
            <option value="Volvo" {{ ($data['car']->brand == "Volvo" ? 'selected' : '') }}>Volvo</option>
            <option value="Peugeot" {{ ($data['car']->brand == "Peugeot" ? 'selected' : '') }}>Peugeot</option>
            <option value="Subaru" {{ ($data['car']->brand == "Subaru" ? 'selected' : '') }}>Subaru</option>
            <option value="Suzuki" {{ ($data['car']->brand == "Suzuki" ? 'selected' : '') }}>Suzuki</option>
            <option value="Chevrolet" {{ ($data['car']->brand == "Chevrolet" ? 'selected' : '') }}>Chevrolet</option>
            <option value="Lexus" {{ ($data['car']->brand == "Lexus" ? 'selected' : '') }}>Lexus</option>
            <option value="Jaguar" {{ ($data['car']->brand == "Jaguar" ? 'selected' : '') }}>Jaguar</option>
            <option value="Land Rover" {{ ($data['car']->brand == "Land Rover" ? 'selected' : '') }}>Land Rover</option>
            <option value="Jeep" {{ ($data['car']->brand == "Jeep" ? 'selected' : '') }}>Jeep</option>
            <option value="Porsche" {{ ($data['car']->brand == "Porsche" ? 'selected' : '') }}>Porsche</option>
            <option value="Mini" {{ ($data['car']->brand == "Mini" ? 'selected' : '') }}>Mini</option>
            <option value="Ferrari" {{ ($data['car']->brand == "Ferrari" ? 'selected' : '') }}>Ferrari</option>
            <option value="Bentley" {{ ($data['car']->brand == "Bentley" ? 'selected' : '') }}>Bentley</option>
            <option value="Lamborghini" {{ ($data['car']->brand == "Lamborghini" ? 'selected' : '') }}>Lamborghini</option>
            <option value="Rolls-Royce" {{ ($data['car']->brand == "Rolls-Royce" ? 'selected' : '') }}>Rolls-Royce</option>
            <option value="Maserati" {{ ($data['car']->brand == "Maserati" ? 'selected' : '') }}>Maserati</option>
            <option value="Bugatti" {{ ($data['car']->brand == "Bugatti" ? 'selected' : '') }}>Bugatti</option>
            <option value="McLaren" {{ ($data['car']->brand == "McLaren" ? 'selected' : '') }}>McLaren</option>
            <option value="Aston Martin" {{ ($data['car']->brand == "Aston Martin" ? 'selected' : '') }}>Aston Martin</option>
            <option value="Alfa Romeo" {{ ($data['car']->brand == "Alfa Romeo" ? 'selected' : '') }}>Alfa Romeo</option>
            <option value="Lotus" {{ ($data['car']->brand == "Lotus" ? 'selected' : '') }}>Lotus</option>
            <option value="Fiat" {{ ($data['car']->brand == "Fiat" ? 'selected' : '') }}>Fiat</option>
            <option value="Citroen" {{ ($data['car']->brand == "Citroen" ? 'selected' : '') }}>Citroen</option>
            <option value="Renault" {{ ($data['car']->brand == "Renault" ? 'selected' : '') }}>Renault</option>
            <option value="Daihatsu" {{ ($data['car']->brand == "Daihatsu" ? 'selected' : '') }}>Daihatsu</option>
            <option value="Isuzu" {{ ($data['car']->brand == "Isuzu" ? 'selected' : '') }}>Isuzu</option>
            <option value="Ssangyong" {{ ($data['car']->brand == "Ssangyong" ? 'selected' : '') }}>Ssangyong</option>
            <option value="Chery" {{ ($data['car']->brand == "Chery" ? 'selected' : '') }}>Chery</option>
            <option value="Geely" {{ ($data['car']->brand == "Geely" ? 'selected' : '') }}>Geely</option>
            <option value="Great Wall" {{ ($data['car']->brand == "Great Wall" ? 'selected' : '') }}>Great Wall</option>
            <option value="Changan" {{ ($data['car']->brand == "Changan" ? 'selected' : '') }}>Changan</option>
            <option value="BYD" {{ ($data['car']->brand == "BYD" ? 'selected' : '') }}>BYD</option>
            <option value="Haval" {{ ($data['car']->brand == "Haval" ? 'selected' : '') }}>Haval</option>
            <option value="Datsun" {{ ($data['car']->brand == "Datsun" ? 'selected' : '') }}>Datsun</option>
            <option value="Infiniti" {{ ($data['car']->brand == "Infiniti" ? 'selected' : '') }}>Infiniti</option>
            <option value="Lancia" {{ ($data['car']->brand == "Lancia" ? 'selected' : '') }}>Lancia</option>
            <option value="Smart" {{ ($data['car']->brand == "Smart" ? 'selected' : '') }}>Smart</option>
            <option value="Saab" {{ ($data['car']->brand == "Saab" ? 'selected' : '') }}>Saab</option>
            <option value="Hummer" {{ ($data['car']->brand == "Hummer" ? 'selected' : '') }}>Hummer</option>
            <option value="Saturn" {{ ($data['car']->brand == "Saturn" ? 'selected' : '') }}>Saturn</option>
            <option value="Pontiac" {{ ($data['car']->brand == "Pontiac" ? 'selected' : '') }}>Pontiac</option>
            <option value="Oldsmobile" {{ ($data['car']->brand == "Oldsmobile" ? 'selected' : '') }}>Oldsmobile</option>
            <option value="Mercury" {{ ($data['car']->brand == "Mercury" ? 'selected' : '') }}>Mercury</option>
            <option value="Lincoln" {{ ($data['car']->brand == "Lincoln" ? 'selected' : '') }}>Lincoln</option>
            <option value="GMC" {{ ($data['car']->brand == "GMC" ? 'selected' : '') }}>GMC</option>
            <option value="Cadillac" {{ ($data['car']->brand == "Cadillac" ? 'selected' : '') }}>Cadillac</option>
            <option value="Buick" {{ ($data['car']->brand == "Buick" ? 'selected' : '') }}>Buick</option>
            <option value="Acura" {{ ($data['car']->brand == "Acura" ? 'selected' : '') }}>Acura</option>
            <option value="Scion" {{ ($data['car']->brand == "Scion" ? 'selected' : '') }}>Scion</option>
            <option value="Plymouth" {{ ($data['car']->brand == "Plymouth" ? 'selected' : '') }}>Plymouth</option>
            </select>
        </div>
      </div>
      <div>
        <div class="form-group">
            <label class="form-label" for="year">Year</label>
            <select class="form-select" id="year" name="year">
            <option value="-" selected disabled>-</option>
            @foreach($data['year'] as $yr)
            <option value="{{ $yr }}" {{ ($data['car']->year == $yr ? 'selected' : '') }}>{{ $yr }}</option>
            @endforeach
            </select>
        </div>
      </div>
      <div>
        <div class="form-group">
          <label>Model</label>
          <input type="text" name="model" id="model" class="form-control" value="{{ $data['car']->model }}">
        </div>
      </div>
      <div>
        <div class="form-group">
          <label>Colour</label>
          <input type="text" name="colour" id="colour" class="form-control" value="{{ $data['car']->colour }}">
        </div>
      </div>
      <div>
        <div class="form-group">
          <label>Registration No.</label>
          <input type="text" name="reNo" id="reNo" class="form-control" value="{{ $data['car']->registration_number }}">
        </div>
      </div>
      <div>
        <div class="form-group">
          <label>Roadtax Due Date</label>
          <input type="date" name="roadtax" id="roadtax" class="form-control" value="{{ $data['car']->date_of_roadtax }}">
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
      <div class="form-group pull-right">
          <input type="submit" name="addtopic" class="form-controlwaves-effect waves-light btn btn-primary btn-sm pull-right" value="submit">
      </div>
  </div>
</form>