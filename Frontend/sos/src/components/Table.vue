<template>
<div class="wrap-table100 container-fluid" >
<div class="row" style="padding-bottom: 20px;">
    <div class="col-md-10">
    <h5 align="left"><b>Gestión de productos</b></h5>
    <div class="d-flex">
        <p align="letf" class="float-left" style="align:left">Nombre del producto</p>
        <div class="form-group col-lg-4 d-flex">
            <input type="text" style="margin-left: 10px" width="200px" id="query" class="form-control"> 
            <button style="padding-left: 10px" class="btn btn-primary" v-on:click="setQuery">Buscar</button>
        </div>
    </div>
    </div>
    <div class="col-md-2">
        <!-- Button trigger modal -->
        <div align="right">
            <b-button v-b-modal.modal-1 class="float-right" style="background-color: #00ccbe; border-color: #00ccbe;">Crear Producto</b-button>

            <b-modal id="modal-1" title="Crear Producto">

                <label><b>Nombre</b></label>
                <input type="text" class="form-control border border-dark" id="name">

                <label><b>Código</b></label>
                <input type="text" class="form-control border border-dark" id="code"  v-model="product2.code">

                <label><b>Existencia</b></label>
                <input type="text" class="form-control border border-dark" id="stock">

                <label><b>Bodega</b></label>

                <select class="form-control" id="warehouse_id" v-model="warehouse">
                <option v-for="(c, index) in warehouses" :key="index" :value="c.id">{{ c.name }}</option>
                </select>
                
                <label><b>Descripción</b></label>
                <textarea class="form-control border border-dark" id="description"> </textarea>

                <label><b>Estado</b></label>
                <select id="status" class="form-control">
                    <option value="active">Activo</option>
                    <option value="pending">Pendiente</option>
                    <option value="inactive">Inactivo</option>
                </select>
<hr>
                <button class="btn btn-primary" v-on:click="store">Guardar</button>
            </b-modal>
        </div>
    </div>
</div>


    <div class="table100">
        <table id="products">
            <thead>
                <tr class="table100-head">
                    <th class="">Nombre del producto</th>
                    <th class=""><center>Código</center></th>
                    <th class=""><center>Existencia</center></th>
                    <th class=""><center>Bodega</center></th>
                    <th class=""><center>Descripción</center></th>
                    <th class=""><center>Editar</center></th>
                    <th class=""><center>Estado</center></th>
                </tr>
            </thead>
                    <paginate name="products" :list="filterList" :per="10" tag="tbody">
                    <tr v-for="product in paginated('products')">
                        <td class=""><img height="16px" src="../assets/check.png"> {{product.name}}</td>
                        <td class=""><center>{{product.code}}</center></td>
                        <td class=""><center>{{product.stock}}</center></td>
                        <td class=""><center>{{product.warehouse.name}}</center></td>
                        <td class=""><center>{{product.description}}</center></td>
                        <td class=""><center><font-awesome-icon v-b-modal.modal-2 v-on:click="edit(product)" class="fa-icon" icon="pencil-alt"/></center></td>
                        <td class="">
                        <div v-if="product.status == 'active'">
                            <div class="status-active"><center>Activo</center></div>
                        </div>
                        <div v-if="product.status == 'inactive'">
                            <div class="status-inactive"><center>Inactivo</center></div>
                        </div>
                        <div v-if="product.status == 'pending'">
                            <div class="status-pending"><center>Pendiente</center></div>
                        </div>
                        </td>
                    </tr>
                    </paginate>
                    <tr class="last"><td colspan="7"><p class="last-text">{{total}} productos registrados. [productos activos: {{active}}] - [Products pendientes por activar: {{pending}}] - [productos inactivos: {{inactive}}]</p></td>
                    </tr>              
                    <b-modal id="modal-2" v-if="product2" title="Editar Producto">

                        <input type="hidden" id="e-id" v-model="product2.id">
            
                        <label><b>Nombre</b></label>
                        <input type="text" class="form-control border border-dark" id="e-name" v-model="product2.name">

                        
                        <label><b>Existencias</b></label>
                        <input type="text" class="form-control border border-dark" id="e-stock"  v-model="product2.stock">

                        
                        <label><b>Description</b></label>
                        <textarea class="form-control border border-dark" id="e-description">{{product2.description}}</textarea>

                        <label><b>Estado</b></label>
                        <select id="e-status" class="form-control">
                            <option value="active">Activo</option>
                            <option value="pending">Pendiente</option>
                            <option value="inactive">Inactivo</option>
                        </select>
                        <hr>
                        <button class="btn btn-primary" v-on:click="edit(product2)">Actualizar</button>
                    </b-modal>
        
        </table>
        
        <br>
        <paginate-links for="products" :show-step-links="true"></paginate-links>
        
    </div>
    <div class="row">
        <div class="col-md-2" align="left" style="padding-top: 30px">
            <img height="120px" src="../assets/pc.png">
        </div>
        <div  class="col-md-3"  align="left" style="padding-top: 30px">
            <p style="color: #f7a851">Estadisticas Generales</p><hr>
            <span>{{cabecera}} Productos en la bodega Cabecera</span><br>
            <span>{{canaveral}} Productos en la bodega Cañaveral</span><br>
            <span>{{centro}} Productos en la bodega Centro</span><br>
        </div>
    </div>
    
</div>

</template>


<script>




import axios from "axios";
//require( 'datatables.net-dt' );
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
var $  = require( 'jquery' );
export default {
    data () {
        return {
            products: [],
            paginate: ['products'],

            query: "",

            total: null,
            active: null,
            pending: null,
            inactive: null,

            cabecera: null,
            canaveral: null,
            centro: null,

            warehouses: null,

            showModal: false,

            product2: [],
        }
  },
  created() {
    axios.get("http://localhost:8000/api/v1/products").then((response) => {
      this.products = response.data.data.products;
      console.log(this.products)
    });

    axios.get("http://localhost:8000/api/v1/warehouse").then((response) => {
      this.cabecera = response.data.data.cabecera
      this.canaveral = response.data.data.canaveral
      this.centro = response.data.data.centro
    })

    axios.get("http://localhost:8000/api/v1/warehouse/all").then((response) => {
      this.warehouses = response.data.data.warehouses
      //console.log("Ok"+JSON.stringify(response.data.data.warehouses));
    })

    axios.get("http://localhost:8000/api/v1/stats").then((response) => {
      this.total = response.data.data.total
      this.active = response.data.data.active
      this.pending = response.data.data.pending
      this.inactive = response.data.data.inactive 
    })

    
  },
  computed: {
      filterList() {
            return this.products.filter(x => new RegExp(this.query, "i").test(x.name))
      }
  },
   methods: {
    setQuery (event) {
      this.query = document.getElementById('query').value;
      filterList()
    },
    refresh (event) {
        axios.get("http://localhost:8000/api/v1/products").then((response) => {
        this.products = response.data.data.products;
        window.location.reload();
    }); 
    },
    edit (product) {
        this.product2 = product
        console.log(product)

        let product3 = {
            id: document.getElementById('e-id').value,
            name: document.getElementById('e-name').value,
            stock: document.getElementById('e-stock').value,
            description: document.getElementById('e-description').value,
            status: document.getElementById('e-status').value,
        }

        const config = {
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
        }

        axios.patch('http://localhost:8000/api/v1/products/'+product3.id, product3)
        .then( (response) =>  {
            console.log(response);
            //this.refresh()
        })
        .catch( (error) => {
            console.log(error);
        });

    },

    store (event) {
        let product = {
            name: document.getElementById('name').value,
            code: document.getElementById('code').value,
            stock: document.getElementById('stock').value,
            warehouse_id: document.getElementById('warehouse_id').value,
            description: document.getElementById('description').value,
            status: document.getElementById('status').value,
        }
        this.products.unshift(product)
        
        console.log("evento");
      axios.post('http://localhost:8000/api/v1/products', product)
        .then( (response) =>  {
            console.log(response);
            this.refresh()
        })
        .catch( (error) => {
            console.log(error);
        });
    }
  },
  mounted() {
        //$('#products').DataTable()
    }
}
</script>


<style media="screen">

.last{
    
    background-color: #0272b2;
    color: white;
}
.last-text{
    padding-top: 5px;
    height: 30px;
}
.paginate-links{
    width:100%;
    list-style: none;
    text-align: center;
}
.paginate-links li {
    display: inline;
    background-color:#0272b2;
    color:white;
    padding:0.5rem;
    margin-left:0.3rem;
    margin-right: 0.3rem;
    cursor:pointer;
    border-radius: 3px;
}
.paginate-result{
    width: 100%;
    text-align:center;
    margin-bottom: 1rem;
}

.fa-icon {
  color: #0272b2;
}

.status-active {
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #00e500; 
  height: 25px;
  color: white;
  border-radius: 10px;
}

.status-pending {
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #FFC107; 
  height: 25px;
  color: white;
  border-radius: 10px;
}

.status-inactive {
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #D32F2F; 
  height: 25px;
  color: white;
  border-radius: 10px;
}


* {
	margin: 0px; 
	padding: 0px; 
	box-sizing: border-box;
}

body, html {
	height: 100%;
	font-family: sans-serif;
    background-color: #f6f6f6;
}

/* ------------------------------------ */
a {
	margin: 0px;
	transition: all 0.4s;
	-webkit-transition: all 0.4s;
  -o-transition: all 0.4s;
  -moz-transition: all 0.4s;
}

a:focus {
	outline: none !important;
}

a:hover {
	text-decoration: none;
}



/* ------------------------------------ */
input {
  display: block;
	outline: none;
	border: none !important;
}

textarea {
  display: block;
  outline: none;
}

textarea:focus, input:focus {
  border-color: transparent !important;
}

/* ------------------------------------ */
button {
	outline: none !important;
	border: none;
	background: transparent;
}

button:hover {
	cursor: pointer;
}

iframe {
	border: none !important;
}




/*//////////////////////////////////////////////////////////////////
[ Utiliti ]*/






/*//////////////////////////////////////////////////////////////////
[ Table ]*/

.limiter {
  width: 100%;
  margin: 0 auto;
}

.container-table100 {
  width: 100%;
  min-height: 100vh;
  background: #c850c0;
  background: -webkit-linear-gradient(45deg, #4158d0, #c850c0);
  background: -o-linear-gradient(45deg, #4158d0, #c850c0);
  background: -moz-linear-gradient(45deg, #4158d0, #c850c0);
  background: linear-gradient(45deg, #4158d0, #c850c0);

  display: -webkit-box;
  display: -webkit-flex;
  display: -moz-box;
  display: -ms-flexbox;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-wrap: wrap;
  padding: 33px 30px;
}

.wrap-table100 {
  width: 1170px;
}

table {
  border-spacing: 1;
  border-collapse: collapse;
  background: white;
  border-radius: 10px;
  overflow: hidden;
  width: 100%;
  margin: 0 auto;
  position: relative;
}
table * {
  position: relative;
}
table td, table th {
  padding-left: 8px;
}
table thead tr {
  height: 60px;
  background: #0272b2;
}
table tbody tr {
  height: 50px;
}
table tbody tr:last-child {
  border: 0;
}
table td, table th {
  text-align: left;
}
table td.l, table th.l {
  text-align: right;
}
table td.c, table th.c {
  text-align: center;
}
table td.r, table th.r {
  text-align: center;
}


.table100-head th{
  font-family: OpenSans-Regular;
  font-size: 18px;
  color: #fff;
  line-height: 1.2;
  font-weight: unset;
}

tbody tr:nth-child(even) {
  background-color: #f5f5f5;
}

tbody tr {
  font-family: OpenSans-Regular;
  font-size: 15px;
  color: #808080;
  line-height: 1.2;
  font-weight: unset;
}

tbody tr:hover {
  color: #555555;
  background-color: #f5f5f5;
  cursor: pointer;
}



@media (max-width: 576px) {
  .container-table100 {
    padding-left: 15px;
    padding-right: 15px;
  }
}


</style>