<?php include("./Includefolder/header.php") ?>

<div class="container mt-4">

   <div class="alert alert-warning alert-dismissible fade show d-none" role="alert">
        <p><strong>Success!</strong>Data Saved Into DataBase.</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
   </div>

   <div class="spinner spinner-border d-none" role="status">
        <span class="visually-hidden">Loading...</span>
   </div>

    <h2>Product Information</h2>
    <form id="insertForm">
        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" name="name" id="name" class="form-control">
        </div>
        <div class="form-group">
            <label for="qty">Quantity</label>
            <input type="number" name="qty" id="qty" class="form-control">
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="text" name="price" id="price" class="form-control">
        </div>
        <div class="form-group">
            <label for="category">Category</label>
            <select name="category" id="category" class="form-control">
                <option value="">Select</option>
                <option value="phone">Phone</option>
                <option value="laptop">Laptop</option>
                <option value="keyboard">Keyboard</option>
                <option value="mouse">Mouse</option>
            </select>
        </div>

        <input type="submit" value="Submit" name="submit" class="btn btn-primary mt-4">
    </form>
</div>

<div class="container">
<table class="table">
  <thead>
    <tr>
      <th>Id</th>
      <th>Product Name</th>
      <th>Quantity</th>
      <th>Price</th>
      <th>Category</th>
      <th>Update</th>
      <th>Delete</th>
    </tr>
  </thead>
  <tbody id="readData">
    
  </tbody>
</table>

</div>

<!-- deleteModal -->
<div class="modal" tabindex="-1" id="deleteModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel">Delete?</h2>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      ARE YOU SURE YOU WANT TO DELETE THIS DATA?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        <button type="button" class="btn btn-danger modalDltBtn" data-bs-dismiss="modal" onclick="dltData(this)">Yes</button>
      </div>
    </div>
  </div>
</div>

<!-- updateData -->
<div class="modal" tabindex="-1" id="updateData" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel">Update Data!</h2>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form id="update_form">
      <input type="hidden" id="up_id">
        <div class="form-group">
            <label for="up_name">Product Name</label>
            <input type="text" id="up_name" class="form-control">
        </div>
        <div class="form-group">
            <label for="up_qty">Quantity</label>
            <input type="number" id="up_qty" class="form-control">
        </div>
        <div class="form-group">
            <label for="up_price">Price</label>
            <input type="text" id="up_price" class="form-control">
        </div>
        <div class="form-group">
            <label for="up_category">Category</label>
            <select id="up_category" class="form-control">
                <option value="">Select</option>
                <option value="phone">Phone</option>
                <option value="laptop">Laptop</option>
                <option value="keyboard">Keyboard</option>
                <option value="mouse">Mouse</option>
            </select>
        </div>

        <input type="submit" value="Submit" class="btn btn-primary mt-4">
    </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="closeUpdateBtn" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
    let insertForm = document.querySelector("#insertForm");

    insertForm.addEventListener("submit", async function (event) {
        event.preventDefault();

        document.querySelector(".spinner").classList.remove("d-none");

        // console.log(event.target);

        let keys = [];
        let values = [];

        for (let input of event.target) {
            // console.log(input.name);
            keys.push(input.name);
            values.push(input.value);
        }

        // console.log(keys);
        // console.log(values);

        let obj = {};

        for (let i=0; i<keys.length-1; i++) {
            // console.log(keys[i]);
            obj[keys[i]] = values[i];
        }

        // console.log(obj);

        let res = await fetch("./apifolder/create.php", {
            method: "POST",
            body: JSON.stringify(obj)
        });

        res = await res.text();

        res = JSON.parse(res);

        document.querySelector(".spinner").classList.add("d-none");

        
        // console.log(res);

        if (res.status == 400) {
            // alert(res.result);
            let errors = res.result;
            let form = document.querySelector("#insertForm");

            // Remove existing error messages
            let existingErrors = form.querySelector("ul");
            if (existingErrors) {
                existingErrors.remove();
            }

            let ul = document.createElement("ul");
            form.prepend(ul);

            errors.forEach(error => {
                 let li = document.createElement("li");
                li.innerText = error;
                ul.append(li);
            })
        } else {
            for (let i=0; i<event.target.length; i++) {
            // console.log(event.target[i]);
            if (event.target[i].name != "submit") {
                    event.target[i].value = "";
            }
        }

            document.querySelector(".alert").classList.remove("d-none");
            showData();
        }
    });

    async function showData () {
        document.querySelector('#readData').innerHTML = "";
        let res = await fetch("./apifolder/read.php");
        res = await res.json();

        for (let data of res) {
            let tr = document.createElement("tr");
            for (let keys in data) {
                let td = document.createElement("td");
                td.innerText = data[keys];
                tr.append(td);
            }

            let updatetd = document.createElement("td");
            let update = document.createElement("button");
            update.innerText = "Update";
            update.classList.add("btn");
            update.classList.add("btn-primary");
            update.setAttribute("data-bs-toggle","modal");
            update.setAttribute("data-bs-target","#updateData");
            update.setAttribute("onclick","updateData(this)");
            updatetd.append(update);

            let deletetd = document.createElement("td");
            let dlt = document.createElement("button");
            dlt.innerText = "Delete";
            dlt.classList.add("btn");
            dlt.classList.add("btn-danger");
            dlt.classList.add('dlt-data');
            dlt.setAttribute("data-bs-toggle","modal");
            dlt.setAttribute("data-bs-target","#deleteModal");
            dlt.setAttribute("onclick","deleteModal(this)");
            deletetd.append(dlt);

            tr.append(updatetd, deletetd);


            document.querySelector("#readData").append(tr);
        }
    }

    window.addEventListener('load', async function() {
        showData();
    });

    function deleteModal (dltBtn) {
        let id = dltBtn.parentElement.parentElement.children[0].innerText;
        let dltModalBtn = document.querySelector(".modalDltBtn").id = id;
    }

    async function dltData(dltBtn) {
        let res = await fetch("./apifolder/delete.php", {
            method: "POST",
            body: dltBtn.id
        })

        res = await res.json();

        // console.log(res);

        if (res.status == 200) {
        showData();
        }
    }

    async function updateData (updateBtn) {
        let id = updateBtn.parentElement.parentElement.children[0].innerText;

        let res = await fetch("./apifolder/get.php", {
            method: "POST",
            body: id
        })

        res = await res.json();

        // console.log(res.result);

        const up_id = document.querySelector("#up_id");
        const up_name = document.querySelector("#up_name");
        const up_qty = document.querySelector("#up_qty");
        const up_price = document.querySelector("#up_price");
        const up_category = document.querySelector("#up_category");

        let updateData = JSON.parse(res.result);
        // console.log(up_name);

        // console.log(updateData);

        up_id.value = updateData.Id;
        up_name.value = updateData.Name;
        up_qty.value = updateData.Quantity;
        up_price.value = updateData.Price;
        up_category.value = updateData.Category;

    }

    let updateForm = document.querySelector("#update_form");

    updateForm.addEventListener("submit", async function (e) {
        e.preventDefault();

        const up_id = document.querySelector("#up_id").value;
        const up_name = document.querySelector("#up_name").value;
        const up_qty = document.querySelector("#up_qty").value;
        const up_price = document.querySelector("#up_price").value;
        const up_category = document.querySelector("#up_category").value;

        let obj = {
            up_id,
            up_name,
            up_qty,
            up_price,
            up_category
        }

        let res = await fetch("./apifolder/update.php",{
            method: "POST",
            body: JSON.stringify(obj)
        })

        res = await res.json();

        if (res.status == 400) {
            // alert(res.result);
            let errors = res.result;

            let update_form = document.querySelector("#update_form");

            let existingErrors = update_form.querySelector("ul");
            if (existingErrors) {
                existingErrors.remove();
            }

            let ul = document.createElement("ul");
            update_form.prepend(ul);

            errors.forEach(error => {
                 let li = document.createElement("li");
                li.innerText = error;
                ul.append(li);
            }) 
        } else {
            document.querySelector("#closeUpdateBtn").click();
            
            alert("Data Update Successfully");
            showData();
        }
    })
</script>

<?php include("./Includefolder/footer.php") ?>
