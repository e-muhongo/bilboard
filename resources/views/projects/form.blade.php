

  <div class="field mb-6">

      <label for="title" class=" label text-sm mb-2 block">Title</label>

      <div class="control">
          <input
                type="text" name="title" id="title"
                class=" input bg-transparent border border-muted-light p-2 w-full text-xs rounded"
                placeholder="Enter the title of your project"
                value="{{ $project->title }}" required>

      </div>

  </div>


  <div class="field mb-6">

      <label for="description" class="label text-sm mb-2 block">Description</label>

      <div class="control">
          <textarea name="description" id="description"
                    class="input bg-transparent border border-muted-light p-2 w-full text-xs rounded"
                     required>{{ $project->description }}</textarea>
      </div>

  </div>

   <div class="field">

      <div class="control">

          <button type="submit" class="button is-link">{{ $buttonText }}</button>
          <a href="{{ $project->path() }}" class="text-default  "> Cancel</a>

      </div>

   </div>


   @if($errors->any())
       <div class="field mt-6">

           @foreach ($errors->all() as $error)

             <li class="text-sm text-red">{{ $error }}</li>

           @endforeach

       </div>
    @endif
