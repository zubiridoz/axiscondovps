<?= $this->extend('layout/main') ?>
<?= $this->section('styles') ?>
<style>
.ob-wrap{max-width:820px;margin:0 auto}
.ob-stepper{display:flex;gap:0;margin-bottom:2.5rem;position:relative;padding:0 1rem}
.ob-step{flex:1;text-align:center;position:relative;z-index:1}
.ob-step .dot{width:36px;height:36px;border-radius:50%;background:#e2e8f0;color:#94a3b8;display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;transition:all .3s;border:2px solid #e2e8f0}
.ob-step.active .dot{background:#3b82f6;color:#fff;border-color:#3b82f6;box-shadow:0 0 0 4px rgba(59,130,246,.15)}
.ob-step.done .dot{background:#10b981;color:#fff;border-color:#10b981}
.ob-step .label{display:block;font-size:.72rem;color:#94a3b8;margin-top:.4rem;font-weight:500}
.ob-step.active .label,.ob-step.done .label{color:#1e293b}
.ob-line{position:absolute;top:18px;left:0;right:0;height:2px;background:#e2e8f0;z-index:0}
.ob-line-fill{height:2px;background:#3b82f6;transition:width .4s}
.ob-card{background:#fff;border-radius:16px;border:1px solid #e2e8f0;padding:2.5rem;box-shadow:0 1px 3px rgba(0,0,0,.04)}
.ob-card h3{font-size:1.25rem;font-weight:700;color:#0f172a;margin-bottom:.35rem}
.ob-card .sub{font-size:.85rem;color:#64748b;margin-bottom:1.75rem}
.ob-input{border:1.5px solid #e2e8f0;border-radius:10px;padding:.65rem 1rem;font-size:.9rem;width:100%;transition:border .2s;font-family:'Inter',sans-serif}
.ob-input:focus{outline:none;border-color:#3b82f6;box-shadow:0 0 0 3px rgba(59,130,246,.1)}
.ob-label{font-size:.8rem;font-weight:600;color:#334155;margin-bottom:.35rem;display:block}
.ob-help{font-size:.72rem;color:#94a3b8;margin-top:.25rem}
.ob-btn{border:none;border-radius:10px;padding:.7rem 2rem;font-weight:600;font-size:.9rem;cursor:pointer;transition:all .2s}
.ob-btn-primary{background:#3b82f6;color:#fff}.ob-btn-primary:hover{background:#2563eb}
.ob-btn-secondary{background:#f1f5f9;color:#475569}.ob-btn-secondary:hover{background:#e2e8f0}
.ob-btn-success{background:#10b981;color:#fff}.ob-btn-success:hover{background:#059669}
.ob-footer{display:flex;justify-content:space-between;align-items:center;margin-top:2rem;padding-top:1.5rem;border-top:1px solid #f1f5f9}
.step-panel{display:none}.step-panel.active{display:block}
.ob-grid{display:grid;gap:1rem}.ob-grid-2{grid-template-columns:1fr 1fr}
.ob-welcome-icon{width:80px;height:80px;border-radius:20px;background:linear-gradient(135deg,#3b82f6,#8b5cf6);display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem}
.ob-upload-zone{border:2px dashed #cbd5e1;border-radius:12px;padding:2rem;text-align:center;cursor:pointer;transition:all .2s;background:#fafbfc}
.ob-upload-zone:hover{border-color:#3b82f6;background:#f0f7ff}
.ob-upload-zone.dragover{border-color:#3b82f6;background:#eff6ff}
.ob-table{width:100%;border-collapse:separate;border-spacing:0;font-size:.82rem}
.ob-table th{background:#f8fafc;padding:.6rem .75rem;font-weight:600;color:#475569;border-bottom:2px solid #e2e8f0;text-align:left}
.ob-table td{padding:.55rem .75rem;border-bottom:1px solid #f1f5f9;color:#334155}
.ob-table tr:hover td{background:#fafbfc}
.ob-badge{display:inline-flex;align-items:center;gap:.3rem;padding:.2rem .6rem;border-radius:6px;font-size:.7rem;font-weight:600}
.ob-badge-blue{background:#eff6ff;color:#3b82f6}
.ob-badge-green{background:#f0fdf4;color:#10b981}
.ob-completion{text-align:center;padding:2rem 0}
.ob-completion .check-circle{width:80px;height:80px;border-radius:50%;background:#10b981;display:inline-flex;align-items:center;justify-content:center;margin-bottom:1.5rem;animation:scaleIn .4s ease}
@keyframes scaleIn{from{transform:scale(0)}to{transform:scale(1)}}
.ob-skip{font-size:.8rem;color:#94a3b8;cursor:pointer;text-decoration:underline}.ob-skip:hover{color:#64748b}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="ob-wrap">
  <!-- Stepper -->
  <div class="ob-stepper" id="obStepper">
    <div class="ob-line"><div class="ob-line-fill" id="obLineFill" style="width:0%"></div></div>
    <div class="ob-step active" data-step="0"><span class="dot"><i class="bi bi-stars"></i></span><span class="label">Inicio</span></div>
    <div class="ob-step" data-step="1"><span class="dot">2</span><span class="label">Nombre</span></div>
    <div class="ob-step" data-step="2"><span class="dot">3</span><span class="label">Dirección</span></div>
    <div class="ob-step" data-step="3"><span class="dot">4</span><span class="label">Finanzas</span></div>
    <div class="ob-step" data-step="4"><span class="dot">5</span><span class="label">Unidades</span></div>
    <div class="ob-step" data-step="5"><span class="dot">6</span><span class="label">Residentes</span></div>
    <div class="ob-step" data-step="6"><span class="dot">7</span><span class="label">Listo</span></div>
  </div>

  <!-- Step 0: Welcome -->
  <div class="step-panel active" data-panel="0">
    <div class="ob-card" style="text-align:center">
      <div class="ob-welcome-icon"><i class="bi bi-building-add text-white" style="font-size:2rem"></i></div>
      <h3>Crear Nueva Sociedad</h3>
      <p class="sub">Configura tu condominio en unos pocos pasos.<br>Podrás modificar todo después desde Configuración.</p>
      <button class="ob-btn ob-btn-primary" onclick="obNext()"><i class="bi bi-arrow-right me-2"></i>Comenzar</button>
    </div>
  </div>

  <!-- Step 1: Name -->
  <div class="step-panel" data-panel="1">
    <div class="ob-card">
      <h3>¿Cómo se llama tu condominio?</h3>
      <p class="sub">Este nombre aparecerá en el dashboard, reportes y comunicados.</p>
      <div>
        <label class="ob-label">Nombre de la comunidad *</label>
        <input type="text" class="ob-input" id="obName" placeholder="Ej: Residencial Las Palmas" maxlength="150" autofocus>
        <div class="ob-help">Máximo 150 caracteres</div>
      </div>
      <div class="ob-footer">
        <button class="ob-btn ob-btn-secondary" onclick="obPrev()"><i class="bi bi-arrow-left me-1"></i>Atrás</button>
        <button class="ob-btn ob-btn-primary" onclick="obValidateAndNext(1)">Siguiente<i class="bi bi-arrow-right ms-1"></i></button>
      </div>
    </div>
  </div>

  <!-- Step 2: Address -->
  <div class="step-panel" data-panel="2">
    <div class="ob-card">
      <h3>Dirección del condominio</h3>
      <p class="sub">Ubicación física de la comunidad.</p>
      <div class="ob-grid ob-grid-2">
        <div><label class="ob-label">Calle y número</label><input type="text" class="ob-input" id="obStreet" placeholder="Av. Insurgentes 500"></div>
        <div><label class="ob-label">Ciudad</label><input type="text" class="ob-input" id="obCity" placeholder="Monterrey"></div>
        <div><label class="ob-label">Estado / Provincia</label><input type="text" class="ob-input" id="obState" placeholder="Nuevo León"></div>
        <div><label class="ob-label">Código postal</label><input type="text" class="ob-input" id="obPostal" placeholder="64000" maxlength="10"></div>
        <div><label class="ob-label">País</label><input type="text" class="ob-input" id="obCountry" placeholder="México" value="México"></div>
      </div>
      <div class="ob-footer">
        <button class="ob-btn ob-btn-secondary" onclick="obPrev()"><i class="bi bi-arrow-left me-1"></i>Atrás</button>
        <div class="d-flex gap-2 align-items-center">
          <span class="ob-skip" onclick="obNext()">Omitir</span>
          <button class="ob-btn ob-btn-primary" onclick="obNext()">Siguiente<i class="bi bi-arrow-right ms-1"></i></button>
        </div>
      </div>
    </div>
  </div>

  <!-- Step 3: Finance -->
  <div class="step-panel" data-panel="3">
    <div class="ob-card">
      <h3>Configuración financiera</h3>
      <p class="sub">Datos bancarios y cuotas. Puedes completar esto después.</p>
      <div class="ob-grid ob-grid-2">
        <div><label class="ob-label">Moneda</label>
          <select class="ob-input" id="obCurrency"><option value="MXN">MXN — Peso Mexicano</option><option value="USD">USD — Dólar</option><option value="COP">COP — Peso Colombiano</option></select>
        </div>
        <div><label class="ob-label">Día de vencimiento</label><input type="number" class="ob-input" id="obDueDay" value="10" min="1" max="28"><div class="ob-help">Día del mes para vencimiento de cuotas</div></div>
        <div><label class="ob-label">Banco</label><input type="text" class="ob-input" id="obBankName" placeholder="BBVA, Banorte..."></div>
        <div><label class="ob-label">CLABE interbancaria</label><input type="text" class="ob-input" id="obClabe" placeholder="18 dígitos" maxlength="18"></div>
        <div><label class="ob-label">RFC</label><input type="text" class="ob-input" id="obRfc" placeholder="RFC del condominio" maxlength="13"></div>
        <div><label class="ob-label">Número de tarjeta</label><input type="text" class="ob-input" id="obBankCard" placeholder="16 dígitos" maxlength="20"></div>
      </div>
      <div class="ob-footer">
        <button class="ob-btn ob-btn-secondary" onclick="obPrev()"><i class="bi bi-arrow-left me-1"></i>Atrás</button>
        <div class="d-flex gap-2 align-items-center">
          <span class="ob-skip" onclick="obNext()">Omitir</span>
          <button class="ob-btn ob-btn-primary" onclick="obNext()">Siguiente<i class="bi bi-arrow-right ms-1"></i></button>
        </div>
      </div>
    </div>
  </div>

  <!-- Step 4: Units -->
  <div class="step-panel" data-panel="4">
    <div class="ob-card">
      <h3>Unidades del condominio</h3>
      <p class="sub">Define las secciones y unidades de tu comunidad (departamentos, casas, locales).</p>
      
      <div class="mb-3">
        <label class="ob-label">¿Cómo deseas registrar las unidades?</label>
        <div class="d-flex gap-3 mt-2">
          <label class="d-flex align-items-center gap-2" style="cursor:pointer"><input type="radio" name="obUnitMode" value="sections" checked onchange="toggleUnitMode()"> <span style="font-size:.85rem">Por Secciones</span></label>
          <label class="d-flex align-items-center gap-2" style="cursor:pointer"><input type="radio" name="obUnitMode" value="csv" onchange="toggleUnitMode()"> <span style="font-size:.85rem">Importar CSV</span></label>
        </div>
      </div>

      <!-- ═══ Modo Secciones ═══ -->
      <div id="obSectionsBox">
        <div class="mb-3 ob-grid ob-grid-2">
          <div>
            <label class="ob-label">Cuota mensual por defecto ($)</label>
            <input type="number" class="ob-input" id="obMonthlyFee" placeholder="2500" step="0.01" style="max-width:250px">
          </div>
          <div></div>
        </div>

        <!-- Header -->
        <div class="d-flex align-items-center gap-2 mb-2" style="padding:0 .25rem">
          <div style="flex:1;font-size:.78rem;font-weight:600;color:#1D4C9D">Nombre de la Sección</div>
          <div style="width:100px;font-size:.78rem;font-weight:600;color:#1D4C9D;text-align:center">Unidades</div>
          <div style="width:36px"></div>
        </div>

        <!-- Rows dinámicas -->
        <div id="obSectionRows">
          <div class="ob-section-row d-flex align-items-center gap-2 mb-2">
            <input type="text" class="ob-input" placeholder="Ej. Torre A, Bloque 1, Privada Lirios" style="flex:1" oninput="updateTotalUnits()">
            <input type="number" class="ob-input" value="1" min="1" max="500" style="width:100px;text-align:center" oninput="updateTotalUnits()">
            <button type="button" class="btn btn-sm" onclick="removeSection(this)" style="width:36px;height:36px;border:1px solid #e2e8f0;border-radius:8px;color:#94a3b8;display:flex;align-items:center;justify-content:center"><i class="bi bi-trash"></i></button>
          </div>
        </div>

        <button type="button" class="ob-btn ob-btn-secondary mt-2" onclick="addSection()" style="font-size:.82rem;padding:.5rem 1.25rem">
          <i class="bi bi-plus-lg me-1"></i> Agregar Sección
        </button>

        <div class="mt-3" style="font-size:.82rem;font-weight:600;color:#1D4C9D">
          Unidades totales: <span id="obTotalUnits">1</span>
        </div>
      </div>

      <!-- ═══ Modo CSV (Estilo módulo Unidades) ═══ -->
      <div id="obCsvUnitBox" class="d-none">
        <div style="border:1px solid #e2e8f0;border-radius:12px;padding:1.5rem;background:#fafbfc">
          <p class="ob-label" style="margin-bottom:1rem;font-size:.88rem;color:#0f172a">Cómo Funciona</p>
          
          <div class="d-flex align-items-start gap-3 mb-3">
            <span style="width:26px;height:26px;border-radius:50%;background:#10b981;color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;flex-shrink:0">1</span>
            <div>
              <strong style="font-size:.85rem;color:#0f172a">Descargar Plantilla</strong>
              <p style="font-size:.78rem;color:#64748b;margin:.2rem 0 .5rem">Obtén un archivo CSV de muestra con el formato correcto para importar unidades</p>
              <a href="<?= base_url('admin/unidades/export') ?>" class="ob-btn ob-btn-success" style="font-size:.78rem;padding:.4rem 1rem;display:inline-flex;align-items:center;gap:.4rem" target="_blank">
                <i class="bi bi-download"></i> Descargar Plantilla
              </a>
            </div>
          </div>

          <div class="d-flex align-items-start gap-3 mb-3">
            <span style="width:26px;height:26px;border-radius:50%;background:#3b82f6;color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;flex-shrink:0">2</span>
            <div>
              <strong style="font-size:.85rem;color:#0f172a">Editar en Hoja de Cálculo</strong>
              <p style="font-size:.78rem;color:#64748b;margin:.2rem 0 0">Abre en Excel/Sheets. Agrega, elimina o modifica unidades y cuotas mensuales según necesites</p>
            </div>
          </div>

          <div class="d-flex align-items-start gap-3 mb-3">
            <span style="width:26px;height:26px;border-radius:50%;background:#f59e0b;color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;flex-shrink:0">3</span>
            <div>
              <strong style="font-size:.85rem;color:#0f172a">Importar Cambios</strong>
              <p style="font-size:.78rem;color:#64748b;margin:.2rem 0 0">Sube el archivo para cargar las unidades al condominio</p>
            </div>
          </div>

          <div class="ob-upload-zone mt-3" id="unitDropZone" onclick="document.getElementById('unitCsvFile').click()">
            <i class="bi bi-cloud-arrow-up" style="font-size:2rem;color:#3b82f6"></i>
            <p style="margin:.5rem 0 0;font-size:.85rem;color:#334155;font-weight:600">Seleccionar Archivo CSV</p>
            <p style="font-size:.72rem;color:#94a3b8">Formato: seccion, unidad, cuota_mensual</p>
            <input type="file" id="unitCsvFile" accept=".csv" class="d-none" onchange="previewUnitCsv(this)">
          </div>
        </div>

        <div id="unitPreviewWrap" class="d-none mt-3" style="max-height:250px;overflow:auto;border:1px solid #e2e8f0;border-radius:10px">
          <table class="ob-table"><thead><tr><th>#</th><th>Sección</th><th>Unidad</th><th>Cuota</th></tr></thead><tbody id="unitPreviewBody"></tbody></table>
        </div>
      </div>

      <input type="hidden" id="obUnitCount" value="0">
      <div class="ob-footer">
        <button class="ob-btn ob-btn-secondary" onclick="obPrev()"><i class="bi bi-arrow-left me-1"></i>Atrás</button>
        <div class="d-flex gap-2 align-items-center">
          <span class="ob-skip" onclick="obNext()">Omitir</span>
          <button class="ob-btn ob-btn-primary" onclick="obValidateAndNext(4)">Siguiente<i class="bi bi-arrow-right ms-1"></i></button>
        </div>
      </div>
    </div>
  </div>

  <!-- Step 5: Residents -->
  <div class="step-panel" data-panel="5">
    <div class="ob-card">
      <h3>Importar residentes</h3>
      <p class="sub">Opcional. Sube un CSV con los datos de tus residentes.</p>
      <div style="display:flex;align-items:center;gap:.65rem;padding:.75rem 1rem;border:1.5px solid #3b82f6;border-radius:10px;background:#f0f7ff;margin-bottom:1.25rem">
        <i class="bi bi-info-circle" style="color:#3b82f6;font-size:1.1rem;flex-shrink:0"></i>
        <span style="font-size:.82rem;color:#1e40af;font-weight:500">Puedes importar residentes ahora o agregarlos más tarde desde el panel de administración</span>
      </div>
      <div class="ob-upload-zone" id="resDropZone" onclick="document.getElementById('resCsvFile').click()">
        <i class="bi bi-people" style="font-size:2rem;color:#94a3b8"></i>
        <p style="margin:.5rem 0 0;font-size:.85rem;color:#64748b">Arrastra tu CSV aquí o haz clic para seleccionar</p>
        <p style="font-size:.72rem;color:#94a3b8">Formato: nombre, correo, unidad</p>
        <input type="file" id="resCsvFile" accept=".csv" class="d-none" onchange="previewResCsv(this)">
      </div>
      <div id="resPreviewWrap" class="d-none mt-3" style="max-height:250px;overflow:auto;border:1px solid #e2e8f0;border-radius:10px">
        <table class="ob-table"><thead><tr><th>#</th><th>Nombre</th><th>Correo</th><th>Unidad</th></tr></thead><tbody id="resPreviewBody"></tbody></table>
      </div>
      <div class="ob-footer">
        <button class="ob-btn ob-btn-secondary" onclick="obPrev()"><i class="bi bi-arrow-left me-1"></i>Atrás</button>
        <div class="d-flex gap-2 align-items-center">
          <span class="ob-skip" onclick="obNext()">Omitir</span>
          <button class="ob-btn ob-btn-primary" onclick="obNext()">Siguiente<i class="bi bi-arrow-right ms-1"></i></button>
        </div>
      </div>
    </div>
  </div>

  <!-- Step 6: Confirmation -->
  <div class="step-panel" data-panel="6">
    <div class="ob-card">
      <h3>Resumen de tu condominio</h3>
      <p class="sub">Revisa los datos antes de crear.</p>
      <div id="obSummary" style="font-size:.88rem;line-height:1.8"></div>
      <div class="ob-footer">
        <button class="ob-btn ob-btn-secondary" onclick="obPrev()"><i class="bi bi-arrow-left me-1"></i>Atrás</button>
        <button class="ob-btn ob-btn-success" id="obSubmitBtn" onclick="obSubmit()"><i class="bi bi-check-lg me-1"></i>Crear Condominio</button>
      </div>
    </div>
    <!-- Success panel (hidden) -->
    <div class="ob-card d-none" id="obSuccessPanel">
      <div class="ob-completion">
        <div class="check-circle"><i class="bi bi-check-lg text-white" style="font-size:2.5rem"></i></div>
        <h3>¡Condominio creado!</h3>
        <p class="sub">Tu nueva comunidad está lista. Ya puedes acceder al dashboard.</p>
        <a href="<?= base_url('admin/dashboard') ?>" class="ob-btn ob-btn-primary"><i class="bi bi-house me-1"></i>Ir al Dashboard</a>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<script>
let obStep=0;const obTotal=7;
let obUnits=[],obResidents=[];

function obGo(s){
  obStep=s;
  document.querySelectorAll('.step-panel').forEach(p=>p.classList.remove('active'));
  document.querySelector(`[data-panel="${s}"]`).classList.add('active');
  document.querySelectorAll('.ob-step').forEach((el,i)=>{
    el.classList.remove('active','done');
    if(i<s)el.classList.add('done');
    if(i===s)el.classList.add('active');
  });
  document.getElementById('obLineFill').style.width=((s/(obTotal-1))*100)+'%';
  if(s===6)buildSummary();
}
function obNext(){obGo(Math.min(obStep+1,obTotal-1))}
function obPrev(){obGo(Math.max(obStep-1,0))}

function obValidateAndNext(step){
  if(step===1){
    const n=document.getElementById('obName').value.trim();
    if(!n){Swal.fire({icon:'warning',title:'Nombre requerido',text:'Escribe el nombre de tu condominio.',confirmButtonColor:'#3b82f6'});return;}
  }
  if(step===4){
    const mode=document.querySelector('input[name="obUnitMode"]:checked').value;
    if(mode==='sections'&&obUnits.length===0){
      const fee=parseFloat(document.getElementById('obMonthlyFee').value)||0;
      obUnits=[];
      document.querySelectorAll('.ob-section-row').forEach(row=>{
        const name=row.querySelector('input[type="text"]').value.trim()||'General';
        const count=parseInt(row.querySelector('input[type="number"]').value)||1;
        for(let i=1;i<=count;i++){
          obUnits.push({name:name+'-'+i,fee:fee,section:name});
        }
      });
      document.getElementById('obUnitCount').value=obUnits.length;
    }
  }
  obNext();
}

function toggleUnitMode(){
  const v=document.querySelector('input[name="obUnitMode"]:checked').value;
  document.getElementById('obSectionsBox').classList.toggle('d-none',v==='csv');
  document.getElementById('obCsvUnitBox').classList.toggle('d-none',v==='sections');
  obUnits=[];
}

function addSection(){
  const container=document.getElementById('obSectionRows');
  const row=document.createElement('div');
  row.className='ob-section-row d-flex align-items-center gap-2 mb-2';
  row.innerHTML=`
    <input type="text" class="ob-input" placeholder="Ej. Torre A, Bloque 1, Privada Lirios" style="flex:1" oninput="updateTotalUnits()">
    <input type="number" class="ob-input" value="1" min="1" max="500" style="width:100px;text-align:center" oninput="updateTotalUnits()">
    <button type="button" class="btn btn-sm" onclick="removeSection(this)" style="width:36px;height:36px;border:1px solid #e2e8f0;border-radius:8px;color:#94a3b8;display:flex;align-items:center;justify-content:center"><i class="bi bi-trash"></i></button>`;
  container.appendChild(row);
  updateTotalUnits();
}

function removeSection(btn){
  const rows=document.querySelectorAll('.ob-section-row');
  if(rows.length<=1){Swal.fire({icon:'info',title:'Mínimo 1 sección',text:'Necesitas al menos una sección.',confirmButtonColor:'#3b82f6'});return;}
  btn.closest('.ob-section-row').remove();
  updateTotalUnits();
}

function updateTotalUnits(){
  let total=0;
  document.querySelectorAll('.ob-section-row').forEach(row=>{
    total+=parseInt(row.querySelector('input[type="number"]').value)||0;
  });
  document.getElementById('obTotalUnits').textContent=total;
  document.getElementById('obUnitCount').value=total;
}

function previewUnitCsv(input){
  if(!input.files[0])return;
  const fd=new FormData();fd.append('file_csv',input.files[0]);
  fetch('<?= base_url("admin/onboarding/units-preview") ?>',{method:'POST',body:fd,headers:{'X-Requested-With':'XMLHttpRequest'}})
  .then(r=>r.json()).then(d=>{
    if(!d.success){Swal.fire('Error',d.message||'Error','error');return;}
    obUnits=d.preview;
    let html='';d.preview.forEach((u,i)=>{html+=`<tr><td>${i+1}</td><td>${u.section||'—'}</td><td>${u.name}</td><td>$${u.fee}</td></tr>`;});
    document.getElementById('unitPreviewBody').innerHTML=html;
    document.getElementById('unitPreviewWrap').classList.remove('d-none');
    document.getElementById('obUnitCount').value=d.total;
  });
}

function previewResCsv(input){
  if(!input.files[0])return;
  const fd=new FormData();fd.append('file_csv',input.files[0]);
  fetch('<?= base_url("admin/onboarding/residents-preview") ?>',{method:'POST',body:fd,headers:{'X-Requested-With':'XMLHttpRequest'}})
  .then(r=>r.json()).then(d=>{
    if(!d.success){Swal.fire('Error',d.message||'Error','error');return;}
    obResidents=d.preview;
    let html='';d.preview.forEach((r,i)=>{html+=`<tr><td>${i+1}</td><td>${r.name}</td><td>${r.email}</td><td>${r.unit||'—'}</td></tr>`;});
    document.getElementById('resPreviewBody').innerHTML=html;
    document.getElementById('resPreviewWrap').classList.remove('d-none');
  });
}

function buildSummary(){
  const name=document.getElementById('obName').value.trim()||'Sin nombre';
  const city=document.getElementById('obCity').value.trim()||'—';
  const curr=document.getElementById('obCurrency').value;
  const bank=document.getElementById('obBankName').value.trim()||'—';
  let html=`
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem 2rem">
      <div><strong>Nombre:</strong> ${name}</div>
      <div><strong>Ciudad:</strong> ${city}</div>
      <div><strong>Moneda:</strong> ${curr}</div>
      <div><strong>Banco:</strong> ${bank}</div>
      <div><strong>Unidades:</strong> <span class="ob-badge ob-badge-blue">${obUnits.length} unidades</span></div>
      <div><strong>Residentes:</strong> <span class="ob-badge ob-badge-green">${obResidents.length} residentes</span></div>
    </div>`;
  document.getElementById('obSummary').innerHTML=html;
}

function obSubmit(){
  const btn=document.getElementById('obSubmitBtn');
  btn.disabled=true;btn.innerHTML='<span class="spinner-border spinner-border-sm me-2"></span>Creando...';
  const payload={
    name:document.getElementById('obName').value.trim(),
    street:document.getElementById('obStreet').value.trim(),
    city:document.getElementById('obCity').value.trim(),
    state:document.getElementById('obState').value.trim(),
    postal_code:document.getElementById('obPostal').value.trim(),
    country:document.getElementById('obCountry').value.trim(),
    currency:document.getElementById('obCurrency').value,
    billing_due_day:parseInt(document.getElementById('obDueDay').value)||10,
    bank_name:document.getElementById('obBankName').value.trim(),
    bank_clabe:document.getElementById('obClabe').value.trim(),
    bank_rfc:document.getElementById('obRfc').value.trim(),
    bank_card:document.getElementById('obBankCard').value.trim(),
    same_amount:document.querySelector('input[name="obUnitMode"]:checked').value==='sections',
    monthly_fee:parseFloat(document.getElementById('obMonthlyFee').value)||0,
    units:obUnits,
    residents:obResidents
  };
  fetch('<?= base_url("admin/onboarding/create") ?>',{
    method:'POST',
    headers:{'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest'},
    body:JSON.stringify(payload)
  }).then(r=>{
    if(!r.ok) throw new Error('Server error: '+r.status);
    const ct=r.headers.get('content-type')||'';
    if(!ct.includes('application/json')) throw new Error('El servidor devolvió una respuesta inesperada.');
    return r.json();
  }).then(d=>{
    if(d.success){
      document.querySelector('[data-panel="6"] .ob-card:first-child').classList.add('d-none');
      document.getElementById('obSuccessPanel').classList.remove('d-none');
      document.querySelectorAll('.ob-step').forEach(el=>el.classList.add('done'));
    }else{
      Swal.fire({icon:'error',title:'Error',text:d.message||'Ocurrió un error.',confirmButtonColor:'#3b82f6'});
      btn.disabled=false;btn.innerHTML='<i class="bi bi-check-lg me-1"></i>Crear Condominio';
    }
  }).catch(e=>{
    Swal.fire({icon:'error',title:'Error de conexión',text:e.message||'Intenta de nuevo.',confirmButtonColor:'#3b82f6'});
    btn.disabled=false;btn.innerHTML='<i class="bi bi-check-lg me-1"></i>Crear Condominio';
  });
}

// Drag & drop
['unitDropZone','resDropZone'].forEach(id=>{
  const z=document.getElementById(id);if(!z)return;
  z.addEventListener('dragover',e=>{e.preventDefault();z.classList.add('dragover')});
  z.addEventListener('dragleave',()=>z.classList.remove('dragover'));
  z.addEventListener('drop',e=>{
    e.preventDefault();z.classList.remove('dragover');
    const f=e.dataTransfer.files[0];if(!f)return;
    const inp=z.querySelector('input[type="file"]');
    const dt=new DataTransfer();dt.items.add(f);inp.files=dt.files;
    inp.dispatchEvent(new Event('change'));
  });
});
</script>
<?= $this->endSection() ?>
