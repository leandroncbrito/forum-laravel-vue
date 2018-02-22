{{--  Editing the question  --}}
<div class="panel panel-default" v-if="editing">
    <div class="panel-heading">
        <div class="level">
            <input type="text" name="title" v-model="form.title" class="form-control">
        </div>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <textarea rows="10" name="body" v-model="form.body" class="form-control"></textarea>
        </div>
    </div>
    <div class="panel-footer">
        <div class="level">
            <button class="btn btn-primary btn-xs level-item" @click="update">Update</button>
            <button class="btn btn-xs level-item" @click="resetForm">Cancel</button> 
            
            @can('update', $thread)
                <form action="{{ $thread->path() }}" method="post" class="ml-a">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button type="submit" class="btn btn-link">Delete</button>
                </form>
            @endcan
        </div>
    </div>    
</div>

{{-- Viewing the question --}}
<div class="panel panel-default" v-else>
    <div class="panel-heading">
        <div class="level">
            <img src="{{ $thread->creator->avatar_path }}" alt="{{ $thread->creator->name }}" width="50" height="50" class="mr-1">
            <span class="flex">
                <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a> posted: <span v-text="title"></span>
            </span>
        </div>
    </div>
    <div class="panel-body" v-text="body"></div>
    <div class="panel-footer" v-if="authorize('owns', thread)">
        <button class="btn btn-xs level-item" @click="editing = true">Edit</button>
    </div>
</div>