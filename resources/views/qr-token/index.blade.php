@extends('layout.app')
@section('title', 'QR Token | Absensi Digital')
@section('content')
<div class="topbar">
    <div>
        <p class="small-title">KONFIGURASI QR</p>
        <h1>QR Token</h1>
        <p class="subtitle">Buat dan kelola QR unik berdasarkan lokasi, tanggal, dan waktu.</p>
    </div>
    <div class="topbar-date">
        <div class="date-icon"><i data-lucide="calendar-days"></i></div>
        <div><span>Hari ini</span><strong>{{ date('d M Y') }}</strong></div>
    </div>
</div>

<div class="page-card">
<div class="page-toolbar"><div><span class="card-label">GENERATE QR</span><h2>QR Token</h2></div><button class="primary-button" onclick="openModal('modalTambahToken')"><i data-lucide="plus"></i> Buat Token</button></div>
<div class="info-strip"><i data-lucide="info"></i><span>Setiap token memiliki lokasi, tanggal, jam berlaku, dan token unik. Data ini sesuai tabel <b>qr_tokens</b>.</span></div>
<div class="token-grid">
@forelse($tokens as $token)
<div class="token-card"><div class="token-head"><div><span class="card-label">TOKEN #{{ $token->id }}</span><h3>{{ $token->qrLocation->nama_lokasi ?? '-' }}</h3></div><span class="status-badge {{ $token->status?'status-active':'status-inactive' }}">{{ $token->status?'Aktif':'Nonaktif' }}</span></div>
<div class="qr-preview" data-token="{{ $token->token }}"><div class="qr-loading">QR</div></div>
<div class="token-meta"><div><span>Tanggal</span><strong>{{ \Carbon\Carbon::parse($token->tanggal)->format('d M Y') }}</strong></div><div><span>Waktu</span><strong>{{ substr($token->waktu_mulai,0,5) }} - {{ substr($token->waktu_berakhir,0,5) }}</strong></div></div>
<div class="token-string"><code>{{ $token->token }}</code><button class="icon-button" onclick="copyToken(@js($token->token))"><i data-lucide="copy"></i></button></div>
<div class="action-row"><button class="secondary-button small" onclick="editToken({{ $token->id }},{{ $token->qr_location_id }},'{{ $token->tanggal }}','{{ substr($token->waktu_mulai,0,5) }}','{{ substr($token->waktu_berakhir,0,5) }}',{{ $token->status?1:0 }})"><i data-lucide="pencil"></i> Edit</button>
<form action="{{ route('qr-token.destroy',$token->id) }}" method="POST" onsubmit="return confirm('Hapus token ini?')">@csrf @method('DELETE')<button class="danger-button small"><i data-lucide="trash-2"></i> Hapus</button></form></div>
</div>
@empty<div class="empty-state full"><i data-lucide="qr-code"></i><strong>Belum ada QR Token</strong><span>Buat token baru setelah lokasi QR tersedia.</span></div>@endforelse
</div></div>

<div id="modalTambahToken" class="modal hidden"><div class="modal-box modal-wide"><button class="modal-close" onclick="closeModal('modalTambahToken')"><i data-lucide="x"></i></button><h3>Buat QR Token</h3>
<form action="{{ route('qr-token.store') }}" method="POST">@csrf
<div class="form-grid"><label>Lokasi QR<select name="qr_location_id" required>@foreach($locations as $loc)<option value="{{ $loc->id }}">{{ $loc->nama_lokasi }} ({{ $loc->kode_lokasi }})</option>@endforeach</select></label><label>Tanggal<input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required></label><label>Waktu Mulai<input type="time" name="waktu_mulai" value="06:00" required></label><label>Waktu Berakhir<input type="time" name="waktu_berakhir" value="15:00" required></label><label>Status<select name="status"><option value="1">Aktif</option><option value="0">Nonaktif</option></select></label></div>
<div class="modal-actions"><button type="button" class="secondary-button" onclick="closeModal('modalTambahToken')">Batal</button><button class="primary-button">Buat Token</button></div></form></div></div>

<div id="modalEditToken" class="modal hidden"><div class="modal-box modal-wide"><button class="modal-close" onclick="closeModal('modalEditToken')"><i data-lucide="x"></i></button><h3>Edit QR Token</h3>
<form id="formEditToken" method="POST">@csrf @method('PUT')
<div class="form-grid"><label>Lokasi QR<select id="t_loc" name="qr_location_id" required>@foreach($locations as $loc)<option value="{{ $loc->id }}">{{ $loc->nama_lokasi }}</option>@endforeach</select></label><label>Tanggal<input id="t_date" type="date" name="tanggal" required></label><label>Waktu Mulai<input id="t_start" type="time" name="waktu_mulai" required></label><label>Waktu Berakhir<input id="t_end" type="time" name="waktu_berakhir" required></label><label>Status<select id="t_status" name="status"><option value="1">Aktif</option><option value="0">Nonaktif</option></select></label></div>
<div class="modal-actions"><button type="button" class="secondary-button" onclick="closeModal('modalEditToken')">Batal</button><button class="primary-button">Update</button></div></form></div></div>
@push('head')<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>@endpush
@push('scripts')<script>
document.querySelectorAll('.qr-preview').forEach(el=>{el.innerHTML='';new QRCode(el,{text:el.dataset.token,width:150,height:150});});
function openModal(id){document.getElementById(id).classList.remove('hidden');} function closeModal(id){document.getElementById(id).classList.add('hidden');}
function editToken(id,loc,date,start,end,status){document.getElementById('formEditToken').action='/qr-token/'+id;document.getElementById('t_loc').value=loc;document.getElementById('t_date').value=date;document.getElementById('t_start').value=start;document.getElementById('t_end').value=end;document.getElementById('t_status').value=status;openModal('modalEditToken');}
function copyToken(token){navigator.clipboard?.writeText(token).then(()=>alert('Token berhasil disalin.'));} 
</script>@endpush

@endsection
