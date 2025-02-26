@extends('layouts.auth.main')
@section('title', 'ตรวจสอบอีเมลของคุณ')
@section('content')
<div class="mb-10">
    <h1 class="text-3xl font-extrabold uppercase !leading-snug text-primary md:text-4xl">ตรวจสอบอีเมลของคุณ</h1>
    <p class="text-base font-bold leading-normal text-white-dark">กรุณาตรวจสอบอีเมลของคุณ</p>
</div>
<div>
    เราได้ส่งลิงก์การรีเซ็ตรหัสผ่านทางอีเมลของคุณแล้ว!
</div>
@endsection
@section('script')
<script>
    setTimeout(
        function(){
            window.location = "{{ route('login') }}" 
        },
    10000);
</script>
@endsection