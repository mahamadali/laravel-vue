<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <button @click="initAddContact()" class="btn btn-primary btn-xs pull-right">
                            + Add New Contact
                        </button>
                        My Contacts
                    </div>

                    <div class="panel-body">
                        <table class="table table-bordered table-striped table-responsive" v-if="Contacts.length > 0">
                            <tbody>
                                <tr>
                                    <th>
                                        No.
                                    </th>
                                    <th>
                                        Full Name
                                    </th>
                                    <th>
                                        Email
                                    </th>
                                    <th>
                                        Address
                                    </th>
                                    <th>
                                        Photo
                                    </th>
                                    <th>
                                        Created At
                                    </th>
                                    <th>
                                        Action
                                    </th>
                                </tr>
                                <tr v-for="(contact, index) in Contacts">
                                    <td>{{ index + 1 }}</td>
                                    <td>
                                        {{ contact.full_name }}
                                    </td>
                                    <td>
                                        {{ contact.email }}
                                    </td>
                                    <td>
                                        {{ contact.address }}
                                    </td>
                                    <td>
                                        <img :src="getPhoto(contact.photo)" height="50">
                                    </td>
                                    <td>
                                        {{ contact.created_at }}
                                    </td>
                                    <td>
                                        <button @click="initUpdate(index)" class="btn btn-success btn-xs">Edit</button>
                                        <button @click="deletecontact(index)" class="btn btn-danger btn-xs">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" id="add_contact_model">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Add New Contact</h4>
                    </div>
                    <div class="modal-body">

                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul>
                                <li v-for="error in errors">{{ error }}</li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <label for="name">Full Name:</label>
                            <input type="text" name="full_name" id="full_name" placeholder="Full Name" class="form-control"
                                   v-model="contact.full_name">
                        </div>
                        <div class="form-group">
                            <label for="address">Address:</label>
                            <textarea name="address" id="address" cols="30" rows="5" class="form-control"
                                      placeholder="Address" v-model="contact.address"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="text" name="email" id="email" placeholder="Email" class="form-control"
                                   v-model="contact.email">
                        </div>
                        <div class="form-group">
                            <label for="file">Image:</label>
                            <input type="file" name="file" id="file" class="form-control" @change="selectFile">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="createcontact" class="btn btn-primary">Save Changes</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div class="modal fade" tabindex="-1" role="dialog" id="update_contact_model">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Update contact</h4>
                    </div>
                    <div class="modal-body">

                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul>
                                <li v-for="error in errors">{{ error }}</li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <label>Full Name:</label>
                            <input type="text" placeholder="Name" class="form-control"
                                   v-model="update_contact.full_name">
                        </div>
                        <div class="form-group">
                            <label for="address">Address:</label>
                            <textarea cols="30" rows="5" class="form-control"
                                      placeholder="Address" v-model="update_contact.address"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Email:</label>
                            <input type="email" placeholder="Email" class="form-control"
                                   v-model="update_contact.email">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="updatecontact" class="btn btn-primary">Save Changes</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
</template>

<script>
    export default {
        data() {
            return {
                Contacts: [],
                contact: {
                    full_name: '',
                    address: '',
                    email: '',
                    photo: ''
                },
                errors: [],
                update_contact: {},
                photo: null
            }
        },
        mounted()
        {
            this.readContacts();
        },
        methods: {
            initAddContact()
            {
                this.errors = [];
                this.reset();
                $("#add_contact_model").modal("show");
            },
            createcontact()
            {
                const data = new FormData();
                data.append('photo', this.photo);
                data.append('full_name', this.contact.full_name);
                data.append('address', this.contact.address);
                data.append('email', this.contact.email);
                axios.post(BASE_URL+'/contact', data)
                        .then(response => {
                            this.reset();
                            this.Contacts.push(response.data.contact);
                            $("#add_contact_model").modal("hide");
                        })
                        .catch(error => {
                            this.errors = [];
                            if (error.response.data.errors.full_name) {
                                this.errors.push(error.response.data.errors.full_name[0]);
                            }

                            if (error.response.data.errors.address) {
                                this.errors.push(error.response.data.errors.address[0]);
                            }

                            if (error.response.data.errors.email) {
                                this.errors.push(error.response.data.errors.email[0]);
                            }

                            if (error.response.data.errors.photo) {
                                this.errors.push(error.response.data.errors.photo[0]);
                            }
                        });
            },
            reset()
            {
                this.contact.full_name = '';
                this.contact.address = '';
                this.contact.email = '';
            },
            readContacts()
            {
                axios.get(BASE_URL+'/contact')
                        .then(response => {
                            this.Contacts = response.data.Contacts;
                        });
            },
            initUpdate(index)
            {
                this.errors = [];
                $("#update_contact_model").modal("show");
                this.update_contact = this.Contacts[index];
            },
            getPhoto(name)
            {
                return BASE_URL+"/images/"+name;
            },
            updatecontact()
            {
                axios.patch(BASE_URL+'/contact/' + this.update_contact.id, {
                    full_name: this.update_contact.full_name,
                    address: this.update_contact.address,
                    email: this.update_contact.email
                })
                        .then(response => {
                            $("#update_contact_model").modal("hide");
                        })
                        .catch(error => {
                            this.errors = [];
                            if (error.response.data.errors.full_name) {
                                this.errors.push(error.response.data.errors.full_name[0]);
                            }

                            if (error.response.data.errors.address) {
                                this.errors.push(error.response.data.errors.address[0]);
                            }

                            if (error.response.data.errors.email) {
                                this.errors.push(error.response.data.errors.email[0]);
                            }
                        });
            },
            deletecontact(index)
            {
                let conf = confirm("Do you ready want to delete this contact?");
                if (conf === true) {
                    axios.delete(BASE_URL+'/contact/' + this.Contacts[index].id)
                    .then(response => {
                        this.Contacts.splice(index, 1);
                    })
                    .catch(error => {
                    });
                }
            },
            selectFile(event) {
                // `files` is always an array because the file input may be in multiple mode
                this.photo = event.target.files[0];
            }
        }
    }
</script>