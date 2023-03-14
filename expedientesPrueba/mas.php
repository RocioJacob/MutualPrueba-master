<div class="row">
                <div class="col">
                  <span class="subtituloDetalles1">ID: </span><span class="subtituloDetalles2"><?php echo $expediente['identificador'].'<br>';?></span>
                </div>

                <div class="col">
                  <span class="subtituloDetalles1">AÑO: </span><span class="subtituloDetalles2"><?php echo $expediente['anio'].'<br>';?></span>
                </div>
            </div>

            <div class="row">
              <div class="col">
                <span class="subtituloDetalles1">TIPO: </span><span class="subtituloDetalles2"><?php echo $expediente['tipo'].'<br>';?></span>
              </div>
              
              <div class="col">
                <span class="subtituloDetalles1">TRAMITE: </span><span class="subtituloDetalles2"><?php echo $expediente['nombre_tramite'].'<br>';?></span>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <span class="subtituloDetalles1">DATOS: </span><span class="subtituloDetalles2"><?php echo $expediente['datos'].'<br>';?></span>
              </div>
            </div>

            <div class="row">
             <div class="col">
              <span class="subtituloDetalles1">EXTRACTO: </span><span class="subtituloDetalles2"><?php echo $expediente['extracto'].'<br>';?></span>
              </div>
            </div>

            <?php 
            if($expediente['documento']!= 0) {
            ?>
              <div class="row">
                <div class="col">
                  <span class="subtituloDetalles1">DOCUMENTO: </span><span class="subtituloDetalles2"><?php echo $expediente['documento'].'<br>';?></span>
                </div>
              </div>
            <?php 
            } 
            ?>

            <?php 
            if($expediente['codigo']!= 0) {
            ?>
              <div class="row">
                <div class="col">
                  <span class="subtituloDetalles1">CÓDIGO: </span><span class="subtituloDetalles2"><?php echo $expediente['codigo'].'<br>';?></span>
                </div>
              </div>
            <?php 
            } 
            ?>

            <?php 
            $fecha = date("d-m-Y", strtotime($expediente['fecha_creacion'])); 
            ?>  
            <div class="row">
              <div class="col">
                <span class="subtituloDetalles1">GENERADO: </span><span class="subtituloDetalles2"><?php echo $fecha.'<br>';?></span>
              </div>
            </div>

            <?php 
            if($expediente['activado']=='0'){?>  
              <div class="row">
                <div class="col">
                  <span class="subtituloDetalles1">ESTADO: </span><span class="subtituloDetalles2"><?php echo "ACTIVADO".'<br>';?></span>
                </div>
              </div>
            <?php 
             } 
      
            if($expediente['activado']=='1'){ ?>
              <div class="row">
                <div class="col">
                  <span class="subtituloDetalles1">ESTADO: </span><span class="subtituloDetalles2"><a style="color: red;"><?php echo "ANULADO".'<br>';?></a></span>
                </div>
              </div>
            <?php 
            } 

            if($expediente['activado']=='2'){ 
            ?>
              <div class="row">
                <div class="col">
                  <span class="subtituloDetalles1">ESTADO: </span><span class="subtituloDetalles2"><a style="color: red;"><?php echo "ARCHIVADO".'<br>';?></a></span>
                </div>
              </div>
            <?php 
            }
            ?>

            <div class="row">
              <div class="col">
                <span class="subtituloDetalles1">PRIORIDAD: </span><span class="subtituloDetalles2"><?php echo $expediente['prioridad'].'<br>';?></span>
              </div>
            </div>

            <?php 
            $fecha = date("d-m-Y", strtotime($expediente['fecha_creacion'])); ?>  
            <div class="row">
              <div class="col">
                <span class="subtituloDetalles1">GENERADO: </span><span class="subtituloDetalles2"><?php echo $fecha.'<br>';?></span>
              </div>
            </div>

            <?php 
            if($expediente['cuit']!= 0){
            ?>
              <div class="row">
                <div class="col">
                  <span class="subtituloDetalles1">CUIT: </span><span class="subtituloDetalles2"><?php echo $expediente['cuit'].'<br>';?></span>
                </div>
              </div>
            <?php 
            } 

            if($expediente['codigo']!= 0){
            ?>
              <div class="row">
                <div class="col">
                  <span class="subtituloDetalles1">CÓDIGO: </span><span class="subtituloDetalles2"><?php echo $expediente['codigo'].'<br>';?></span>
                </div>
              </div>
            <?php 
            } 

            $fecha = date("d-m-Y", strtotime($expediente['fecha_creacion'])); ?>  
            <div class="row">
              <div class="col">
                <span class="subtituloDetalles1">GENERADO: </span><span class="subtituloDetalles2"><?php echo $fecha.'<br>';?></span>
              </div>
            </div>

            <?php 
            if($expediente['activado']=='0'){
            ?>  
              <div class="row">
                <div class="col">
                  <span class="subtituloDetalles1">ESTADO: </span><span class="subtituloDetalles2"><?php echo "ACTIVADO".'<br>';?></span>
                </div>
              </div>
            <?php 
            } 

            if($expediente['activado']=='1'){ 
            ?>
              <div class="row">
                <div class="col">
                  <span class="subtituloDetalles1">ESTADO: </span><span class="subtituloDetalles2"><a style="color: red;"><?php echo "ANULADO".'<br>';?></a></span>
                </div>
              </div>
            <?php 
            } 

            if($expediente['activado']=='2'){ 
            ?>
              <div class="row">
                <div class="col">
                  <span class="subtituloDetalles1">ESTADO: </span><span class="subtituloDetalles2"><a style="color: red;"><?php echo "ARCHIVADO".'<br>';?></a></span>
                </div>
              </div>
            <?php 
            }
            ?>



          <div class="row">
            <div class="col">
              <span class="subtituloDetalles1">PRIORIDAD: </span><span class="subtituloDetalles2"><?php echo $expediente['prioridad'].'<br>';?></span>
            </div>
          </div>


          <?php //Si el expediente necesita o no la autorizacion
          if(tieneAutorizacion($expediente['nombre_tramite'])) { 
            if($expediente['autorizado']=='0'){
              $autorizado = "SI";
              $responsable = $expediente['usuario_autorizado'];
          ?>
              <div class="row">
                <div class="col">
                  <span class="subtituloDetalles1">CON AUTORIZACIÓN: </span><span class="subtituloDetalles2"><?php echo "SI".'<br>'?></span>
                </div>
              </div>

              <div class="row">
                <div class="col">
                  <span>AUTORIZADO POR: </span><span class="subtituloDetalles2"><?php echo $responsable.'<br>'?></span>
                </div>
              </div>
            <?php
            }
            else{
              $autorizado = "PENDIENTE";
            ?>
              <div class="row">
                <div class="col">
                  <span class="subtituloDetalles1">CON AUTORIZACIÓN: </span><span class="subtituloDetalles2"><?php echo "SI".'<br>'?></span>
                </div>
              </div>

              <div class="row">
                <div class="col">
                  <span class="subtituloDetalles1">AUTORIZADO POR: </span><span class="subtituloDetalles2"><?php echo "PENDIENTE".'<br>'?></span>
                </div>
              </div>
            <?php
            }
          } 
          else{
          ?>
            <div class="row">
              <div class="col">
                <span class="subtituloDetalles1">CON AUTORIZACIÓN: </span><span class="subtituloDetalles2"><?php echo "NO REQUIERE".'<br>';?></span>
              </div>
            </div>
          <?php 
          } 

          $estadoActual = estadoActual($identificador);
          ?>
          <div class="row">
            <div class="col">
              <span class="subtituloDetalles1">ACTUALMENTE: </span><span class="subtituloDetalles2">
                <?php echo $estadoActual.'<br>';?></span>
            </div>
          </div>

          <div class="row">
            <div class="col"><span class="subtituloDetalles1">ES ANUAL?</span><span class="subtituloDetalles2">
              <?php 
              if($expediente['es_anual']=='0'){ 
                echo "SI".'<br>';
              ?>
            </span></div>
              <?php 
              }else{ 
               echo "NO".'<br>';
              ?>
            </span></div>
              <?php 
              } 
              ?>
          </div>