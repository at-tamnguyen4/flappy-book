@extends('backend.layouts.master')
@section('title')
    {{ __('posts.list_posts') }}
@endsection
@section('content')
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        {{ __('posts.list_posts') }}
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('admin.home.index') }}"><i class="fa fa-dashboard"></i> {{ __('posts.home') }}</a></li>
        <li><a href="{{ route('borrows.index') }}">{{ __('posts.posts') }}</a></li>
        <li class="active">{{ __('posts.list_posts') }}</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example2" class="table table-bordered table-hover">
                <thead>
                 <tr>
                  <th class="text-center" width="5%">
                    {{ __('posts.no') }}
                  </th>
                  <th class="text-center">
                    {{ __('posts.short_content') }}
                  </th>
                  <th class="text-center" width="10%">
                    {{ __('posts.status') }}
                  </th>
                  <th width="10%" class="text-center">
                    {{ __('posts.user_name') }}
                  </th>
                  <th class="text-center" width="12%">
                    {{ __('posts.post_date') }}
                  </th>
                    <th class="text-center" width="12%">
                    {{ __('posts.total_comment') }}
                  </th>
                  <th class="text-center" width="15%">
                    {{ __('posts.options') }}
                  </th>
                </tr>
                </thead>
                <tbody>
                  @foreach ($posts as $post)
                    <tr>
                      <td class="text-center">{{ $post->id }}</td>
                      <td>{!! \Illuminate\Support\Str::words($post->content, 7,'...')  !!}</td>
                      <td class="text-center">
                        @switch($post->status)
                          @case(App\Model\Post::REVIEW_TYPE)
                            {{ __('posts.review') }}
                            @break
                          @case(App\Model\Post::STATUS_TYPE)
                            {{ __('posts.status') }}
                            @break
                          @case(App\Model\Post::FIND_TYPE)
                            {{ __('posts.find_book') }}
                            @break
                        @endswitch
                      </td>
                      <td>{{ $post->name }}</td>
                      <td class="text-center">{{ date('H:A d/m/Y', strtotime($post->created_at)) }}</td>
                      <td class="text-center">{{ $post->comments_count }}</td>
                      <td class="text-center">
                        <div class="btn-option text-center">
                          <a href="" class="btn btn-primary btn-flat fa fa-pencil"></a>&nbsp;&nbsp;
                          <form method="POST" action="" class="inline">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <button type="button" class="btn btn-danger btn-flat fa fa-trash-o btn-delete-item"
                              data-title=""
                              data-confirm="">
                            </button>
                          </form> 
                        </div>
                      </td>
                    </tr>
                   @endforeach  
                </tbody>
              </table>
              <!-- .pagination -->
              <div class="text-right">
                <nav aria-label="...">
                    <ul class="pagination">
                      {{ $posts->links() }}
                    </ul>
                </nav>
              </div>
              <!-- /.pagination -->
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection
