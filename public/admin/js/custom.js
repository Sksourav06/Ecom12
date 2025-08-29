$(document).ready(function () {

    // call datatable class
    // $('#sections').DataTable();
    // $('#categories').DataTable();
    // $('#brands').DataTable();
    // $('#products').DataTable();

    // $(".nav-item").removeClass("active");
    // $(".nav-link").removeClass("active");
    // Check Admin Password is correct or not
    $("#current_pwd").keyup(function () {
        var current_pwd = $("#current_pwd").val();
        /*alert(current_password);*/
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '/admin/verify-password',
            data: { current_pwd: current_pwd },
            success: function (resp) {
                if (resp == "false") {
                    $("#verifyPwd").html("<font color='red'>Current Password is Incorrect!</font>");
                } else if (resp == "true") {
                    $("#verifyPwd").html("<font color='green'>Current Password is Correct!</font>");
                }
            }, error: function () {
                alert('Error');
            }

        });
    })

    //deleteprofile photo
    $(document).on("click", "#deleteProfileImage", function () {
        if (confirm('Are you sure you want to remove your Profile Image?')) {
            var admin_id = $(this).data("admin-id");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: 'delete-profile-image',
                data: {admin_id: admin_id},
                success: function (resp) {
                    // alert(resp);
                    if (resp['status'] == true) {
                        alert(resp['message']);
                        $('#profileImageBlock').remove();
                    }
                }, error: function () {
                    alert("Error occurred while deleting the image.");
                }

            });
        }
    });


    // Update SubAdmin Status
    $(document).on("click", ".updateSubadminStatus", function () {
        var status = $(this).children("i").data("status");  // data-status ব্যবহার করুন
        var subadmin_id = $(this).data("subadmin_id");      // data-subadmin_id ব্যবহার করুন

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '/admin/update-subadmin-status',
            data: {status: status, subadmin_id: subadmin_id},
            success: function (resp) {
                if (resp.status !== false) {
                    // রেসপন্স অনুযায়ী আইকন এবং স্ট্যাটাস আপডেট করা
                    if (resp.status == 0) {
                        $("a[data-subadmin_id='" + subadmin_id + "']").html("<i class='fas fa-toggle-off' style='color:grey' data-status='Inactive'></i>");
                    } else if (resp.status == 1) {
                        $("a[data-subadmin_id='" + subadmin_id + "']").html("<i class='fas fa-toggle-on' style='color:#3f6ed3' data-status='Active'></i>");
                    }
                } else {
                    alert("Error: Could not update the subadmin status.");
                }
            },
            error: function () {
                alert("Error occurred while updating the subadmin status.");
            }
        });
    });
    $(document).on("click", ".updateFilterStatus", function () {
        var status = $(this).children("i").data("status");  // data-status ব্যবহার করুন
        var filter_id = $(this).data("filter_id");      // data-subadmin_id ব্যবহার করুন

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '/admin/update-filter-status',
            data: {status: status, filter_id: filter_id},
            success: function (resp) {
                if (resp.status !== false) {
                    // রেসপন্স অনুযায়ী আইকন এবং স্ট্যাটাস আপডেট করা
                    if (resp.status == 0) {
                        $("a[data-filter_id='" + filter_id + "']").html("<i class='fas fa-toggle-off' style='color:grey' data-status='Inactive'></i>");
                    } else if (resp.status == 1) {
                        $("a[data-filter_id='" + filter_id + "']").html("<i class='fas fa-toggle-on' style='color:#3f6ed3' data-status='Active'></i>");
                    }
                } else {
                    alert("Error: Could not update the filter status.");
                }
            },
            error: function () {
                alert("Error occurred while updating the filter status.");
            }
        });
    });
    $(document).on("click", ".updateCategoryStatus", function () {
        var status = $(this).children("i").data("status");  // data-status ব্যবহার করুন
        var category_id = $(this).data("category-id");      // data-subadmin_id ব্যবহার করুন

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '/admin/update-category-status',
            data: {status: status, category_id: category_id},
            success: function (resp) {
                if (resp.status !== false) {
                    // রেসপন্স অনুযায়ী আইকন এবং স্ট্যাটাস আপডেট করা
                    if (resp.status == 0) {
                        $("a[data-category-id='" + category_id + "']").html("<i class='fas fa-toggle-off' style='color:grey' data-status='Inactive'></i>");
                    } else if (resp.status == 1) {
                        $("a[data-category-id='" + category_id + "']").html("<i class='fas fa-toggle-on' style='color:#3f6ed3' data-status='Active'></i>");
                    }
                } else {
                    alert("Error: Could not update the category status.");
                }
            },
            error: function () {
                alert("Error occurred while updating the category status.");
            }
        });
    });

    //Product
    $(document).on("click", ".updateProductStatus", function () {
        var status = $(this).children("i").data("status");  // data-status ব্যবহার করুন
        var product_id = $(this).data("product-id");      // data-subadmin_id ব্যবহার করুন

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '/admin/update-product-status',
            data: {status: status, product_id: product_id},
            success: function (resp) {
                if (resp.status !== false) {
                    // রেসপন্স অনুযায়ী আইকন এবং স্ট্যাটাস আপডেট করা
                    if (resp.status == 0) {
                        $("a[data-product-id='" + product_id + "']").html("<i class='fas fa-toggle-off' style='color:grey' data-status='Inactive'></i>");
                    } else if (resp.status == 1) {
                        $("a[data-product-id='" + product_id + "']").html("<i class='fas fa-toggle-on' style='color:#3f6ed3' data-status='Active'></i>");
                    }
                } else {
                    alert("Error: Could not update the category status.");
                }
            },
            error: function () {
                alert("Error occurred while updating the category status.");
            }
        });
    });
    $(document).on("click", ".updatebrandStatus", function () {
        var status = $(this).children("i").data("status");  // data-status ব্যবহার করুন
        var brand_id = $(this).data("brand-id");      // data-subadmin_id ব্যবহার করুন

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '/admin/update-brand-status',
            data: {status: status, brand_id: brand_id},
            success: function (resp) {
                if (resp.status !== false) {
                    // রেসপন্স অনুযায়ী আইকন এবং স্ট্যাটাস আপডেট করা
                    if (resp.status == 0) {
                        $("a[data-brand-id='" + brand_id + "']").html("<i class='fas fa-toggle-off' style='color:grey' data-status='Inactive'></i>");
                    } else if (resp.status == 1) {
                        $("a[data-brand-id='" + brand_id + "']").html("<i class='fas fa-toggle-on' style='color:#3f6ed3' data-status='Active'></i>");
                    }
                } else {
                    alert("Error: Could not update the category status.");
                }
            },
            error: function () {
                alert("Error occurred while updating the category status.");
            }
        });
    });
    $(document).on("click", ".updateBannerStatus", function () {
        var status = $(this).children("i").data("status");  // data-status ব্যবহার করুন
        var banner_id = $(this).data("banner-id");          // data-banner-id ব্যবহার করুন

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '/admin/update-banner-status',
            data: {status: status, banner_id: banner_id},  // সঠিকভাবে ডেটা পাঠানো হচ্ছে
            success: function (resp) {
                if (resp.status !== false) {
                    // রেসপন্স অনুযায়ী আইকন এবং স্ট্যাটাস আপডেট করা
                    if (resp.status == 0) {
                        $("a[data-banner-id='" + banner_id + "']").html("<i class='fas fa-toggle-off' style='color:grey' data-status='Inactive'></i>");
                    } else if (resp.status == 1) {
                        $("a[data-banner-id='" + banner_id + "']").html("<i class='fas fa-toggle-on' style='color:#3f6ed3' data-status='Active'></i>");
                    }
                } else {
                    alert("Error: Could not update the banner status.");
                }
            },
            error: function () {
                alert("Error occurred while updating the banner status.");
            }
        });
    });
    $(document).on("click", ".deleteCategoryImage", function () {
        if (confirm('Are you sure want to remove this category image?')) {  // data-status ব্যবহার করুন
            var category_id = $(this).data("category-id");      // data-subadmin_id ব্যবহার করুন

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '/admin/delete-category-image',
                data: {category_id: category_id},
                success: function (resp) {
                    if (resp.status !== true) {
                        alert(resp['message']);
                        $('#categoryImageBlock').remove();
                    }
                },
                error: function () {
                    alert("Error occurred while updating the category status.");
                }
            });
        }
    });


    // // Update Section Status
    // $(document).on("click", ".updateSectionStatus", function () {
    //     var status = $(this).children("i").attr("status");
    //     var section_id = $(this).attr("section_id");
    //     $.ajax({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         type: 'post',
    //         url: '/admin/update-section-status',
    //         data: { status: status, section_id: section_id },
    //         success: function (resp) {
    //             // alert(resp);
    //             if (resp['status'] == 0) {
    //                 $("#section-" + section_id).html("<i style='font-size:25px;' class='mdi mdi-bookmark-outline' status='Inactive'></i>");
    //             } else if (resp['status'] == 1) {
    //                 $("#section-" + section_id).html("<i style='font-size:25px;' class='mdi mdi-bookmark-check' status='Active'></i>");
    //             }
    //         }, error: function () {
    //             alert("Error");
    //         }
    //     })
    // });

    // // Update Category Status
    // $(document).on("click", ".updateCategoryStatus", function () {
    //     var status = $(this).children("i").attr("status");
    //     var category_id = $(this).attr("category_id");
    //     $.ajax({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         type: 'post',
    //         url: '/admin/update-category-status',
    //         data: { status: status, category_id: category_id },
    //         success: function (resp) {
    //             // alert(resp);
    //             if (resp['status'] == 0) {
    //                 $("#category-" + category_id).html("<i style='font-size:25px;' class='mdi mdi-bookmark-outline' status='Inactive'></i>");
    //             } else if (resp['status'] == 1) {
    //                 $("#category-" + category_id).html("<i style='font-size:25px;' class='mdi mdi-bookmark-check' status='Active'></i>");
    //             }
    //         }, error: function () {
    //             alert("Error");
    //         }
    //     })
    // });

    // // Update Brand Status
    // $(document).on("click", ".updateBrandStatus", function () {
    //     var status = $(this).children("i").attr("status");
    //     var brand_id = $(this).attr("brand_id");
    //     $.ajax({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         type: 'post',
    //         url: '/admin/update-brand-status',
    //         data: { status: status, brand_id: brand_id },
    //         success: function (resp) {
    //             // alert(resp);
    //             if (resp['status'] == 0) {
    //                 $("#brand-" + brand_id).html("<i style='font-size:25px;' class='mdi mdi-bookmark-outline' status='Inactive'></i>");
    //             } else if (resp['status'] == 1) {
    //                 $("#brand-" + brand_id).html("<i style='font-size:25px;' class='mdi mdi-bookmark-check' status='Active'></i>");
    //             }
    //         }, error: function () {
    //             alert("Error");
    //         }
    //     })
    // });

    // // Update Product Status
    // $(document).on("click", ".updateProductStatus", function () {
    //     var status = $(this).children("i").attr("status");
    //     var product_id = $(this).attr("product_id");
    //     $.ajax({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         type: 'post',
    //         url: '/admin/update-product-status',
    //         data: { status: status, product_id: product_id },
    //         success: function (resp) {
    //             // alert(resp);
    //             if (resp['status'] == 0) {
    //                 $("#product-" + product_id).html("<i style='font-size:25px;' class='mdi mdi-bookmark-outline' status='Inactive'></i>");
    //             } else if (resp['status'] == 1) {
    //                 $("#product-" + product_id).html("<i style='font-size:25px;' class='mdi mdi-bookmark-check' status='Active'></i>");
    //             }
    //         }, error: function () {
    //             alert("Error");
    //         }
    //     })
    // });

    // // Update Attribute Status
    // $(document).on("click", ".updateAttributeStatus", function () {
    //     var status = $(this).children("i").attr("status");
    //     var attribute_id = $(this).attr("attribute_id");
    //     $.ajax({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         type: 'post',
    //         url: '/admin/update-attribute-status',
    //         data: { status: status, attribute_id: attribute_id },
    //         success: function (resp) {
    //             // alert(resp);
    //             if (resp['status'] == 0) {
    //                 $("#attribute-" + attribute_id).html("<i style='font-size:25px;' class='mdi mdi-bookmark-outline' status='Inactive'></i>");
    //             } else if (resp['status'] == 1) {
    //                 $("#attribute-" + attribute_id).html("<i style='font-size:25px;' class='mdi mdi-bookmark-check' status='Active'></i>");
    //             }
    //         }, error: function () {
    //             alert("Error");
    //         }
    //     })
    // });

    // // Update Image Status
    // $(document).on("click", ".updateImageStatus", function () {
    //     var status = $(this).children("i").attr("status");
    //     var image_id = $(this).attr("image_id");
    //     $.ajax({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         type: 'post',
    //         url: '/admin/update-image-status',
    //         data: { status: status, image_id: image_id },
    //         success: function (resp) {
    //             // alert(resp);
    //             if (resp['status'] == 0) {
    //                 $("#image-" + image_id).html("<i style='font-size:25px;' class='mdi mdi-bookmark-outline' status='Inactive'></i>");
    //             } else if (resp['status'] == 1) {
    //                 $("#image-" + image_id).html("<i style='font-size:25px;' class='mdi mdi-bookmark-check' status='Active'></i>");
    //             }
    //         }, error: function () {
    //             alert("Error");
    //         }
    //     })
    // });

    // // Confirm Deletion (Simple Javascript)
    // /*$(".confirmDelete").click(function(){
    //     var title = $(this).attr("title");
    //     if(confirm("Are you sure to delete this "+title+"?")){
    //         return true;
    //     }else{
    //         return false;
    //     }
    // })*/

    // Confirm Deletion (SweetAlert Library)
    // $(document).on("click", ".confirmDelete", function (e) {
    //     e.preventDefault();

    //     let button = $(this); // ✅ এখানে ঠিক করলাম
    //     let module = button.data('module');
    //     let moduleid = button.data('id');
    //     let form = button.closest('form');
    //     let redirectUrl = "/admin/delete-" + module + "/" + moduleid;

    //     Swal.fire({
    //         title: 'Are you sure?',
    //         text: "You won't be able to revert this!",
    //         icon: 'warning',
    //         showCancelButton: true,
    //         confirmButtonColor: '#3085d6',
    //         cancelButtonColor: '#d33',
    //         confirmButtonText: 'Yes, delete it!'
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             if (form.length > 0) {
    //                 form.submit();
    //             } else {
    //                 window.location.href = redirectUrl;
    //             }
    //         }
    //     });
    // });
    // $(document).on("click", ".confirmDelete", function (e) {
    //     e.preventDefault();

    //     let button = $(this); // ✅ এখানে ঠিক করলাম
    //     let module = button.data('module');
    //     let moduleid = button.data('id');
    //     let form = button.closest('form');
    //     let redirectUrl = "/admin/delete-" + module + "/" + moduleid;

    //     Swal.fire({
    //         title: 'Are you sure?',
    //         text: "You won't be able to revert this!",
    //         icon: 'warning',
    //         showCancelButton: true,
    //         confirmButtonColor: '#3085d6',
    //         cancelButtonColor: '#d33',
    //         confirmButtonText: 'Yes, delete it!'
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             if (form.length > 0) {
    //                 form.submit();
    //             } else {
    //                 window.location.href = redirectUrl;
    //             }
    //         }
    //     });
    // });

    //product
    $(document).on("click", ".confirmDelete", function (e) {
        e.preventDefault();

        let button = $(this); // ✅ এখানে ঠিক করলাম
        let module = button.data('module');
        let moduleid = button.data('id');
        let form = button.closest('form');
        let redirectUrl = "/admin/delete-" + module + "/" + moduleid;

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                if (form.length > 0) {
                    form.submit();
                } else {
                    window.location.href = redirectUrl;
                }
            }
        });
    });
    document.addEventListener('DOMContentLoaded', function () {
        // সব ডিলিট বাটনে ইভেন্ট লিসেনার লাগাবে
        document.querySelectorAll('.confirmDlete').forEach(function (element) {
            element.addEventListener('click', function (e) {
                e.preventDefault();

                const module = this.dataset.module; // যেমন: attribute
                const id = this.dataset.id;         // id

                Swal.fire({
                    title: 'Are you sure?',
                    text: `You want to delete this ${module}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // যদি AJAX দিয়ে delete করো:
                        // অথবা নিচের লাইন দিয়ে redirect করো
                        window.location.href = `/admin/delete-${module}/${id}`;
                    }
                });
            });
        });
    });

    Dropzone.autoDiscover = false;

    const mainImageDropzone = new Dropzone("#mainImageDropzone", {
        url: "{{ route('products.upload.image') }}", // ✅ Ensure route exists in web.php
        paramName: "file", // ✅ Must be "file" to match request()->file('file')
        maxFiles: 1,
        acceptedFiles: ".jpg,.jpeg,.png,.webp,.gif,.bmp",
        addRemoveLinks: true,
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        success: function (file, response) {
            // ✅ Set the hidden input with returned file name
            document.getElementById('main_image_hidden').value = response.file_name;
        },
        error: function (file, response) {
            alert(response.message || "Upload failed.");
        },
        maxfilesexceeded: function (file) {
            this.removeAllFiles();
            this.addFile(file);
        }
    });
    Dropzone.autoDiscover = false;

    const productVideoDropzone = new Dropzone("#productVideoDropzone", {
        url: "{{ route('products.upload.video') }}", // This route must exist
        paramName: "file",
        maxFiles: 1,
        acceptedFiles: ".mp4,.avi,.mov,.wmv,.mkv",
        addRemoveLinks: true,
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        success: function (file, response) {
            document.getElementById('product_video_hidden').value = response.file_name;
        },
        error: function (file, response) {
            alert(response.message || "Upload failed.");
        },
        maxfilesexceeded: function (file) {
            this.removeAllFiles();
            this.addFile(file);
        }
    });

    document.addEventListener("deleteProductMainImage", function () {
        $(document).on("click", ".confirmDelete", function () {
            const module = $(this).data('module');
            const id = $(this).data('id');

            Swal.fire({
                title: "Are you sure?",
                text: "You want to delete this image?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('products.delete.main.image') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id
                        },
                        success: function (response) {
                            if (response.status) {
                                Swal.fire(
                                    "Deleted!",
                                    response.message,
                                    "success"
                                ).then(() => {
                                    location.reload(); // অথবা শুধু image tag remove করতে পারো
                                });
                            } else {
                                Swal.fire("Error", response.message, "error");
                            }
                        },
                        error: function () {
                            Swal.fire("Error", "Something went wrong!", "error");
                        }
                    });
                }
            });
        });
    });

    //Add/Remove Attribute

    $(document).ready(function () {
        const maxField = 10;
        const wrapper = $('.field_wrapper');
        const addButton = '.add_button';
        const removeTmpl = '<a href="javascript:void(0);" class="btn btn-sm btn-danger remove_button mt-1"><i class="fas fa-minus"></i></a>';

        $(document).on('click', addButton, function (e) {
            e.preventDefault();
            if (wrapper.find('.attribute-row').length >= maxField) return;

            const row = $(this).closest('.attribute-row').clone();
            row.find('input').val('');
            row.find(addButton).replaceWith(removeTmpl);
            wrapper.append(row);
        });

        wrapper.on('click', '.remove_button', function (e) {
            e.preventDefault();
            $(this).closest('.attribute-row').remove();
        });
    });

    // Video Dropzone
    // const videoDropzone = new Dropzone("#productVideoDropzone", {
    //     url: "{{ route('products.upload.video') }}",
    //     paramName: "file",
    //     maxFiles: 1,
    //     acceptedFiles: 'video/mp4,video/x-m4v,video/*',
    //     addRemoveLinks: true,
    //     headers: {
    //         'X-CSRF-TOKEN': csrfToken
    //     },
    //     success: function (file, response) {
    //         document.getElementById('product_video_hidden').value = response.file_name;
    //     }
    // });


    // // Append Categories level
    // $("#section_id").change(function () {
    //     var section_id = $(this).val();
    //     $.ajax({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         type: 'get',
    //         url: '/admin/append-categories-level',
    //         data: { section_id: section_id },
    //         success: function (resp) {
    //             $("#appendCategoriesLevel").html(resp);
    //         }, error: function () {
    //             alert("Error");
    //         }
    //     })
    // });

    // // Products Attributes Add/Remove Script
    // var maxField = 10; //Input fields increment limitation
    // var addButton = $('.add_button'); //Add button selector
    // var wrapper = $('.field_wrapper'); //Input field wrapper
    // var fieldHTML = '<div><div style="height:10px;"></div><input type="text" name="size[]" placeholder="Size" style="width: 120px;"/>&nbsp;<input type="text" name="sku[]" placeholder="SKU" style="width: 120px;"/>&nbsp;<input type="text" name="price[]" placeholder="Price" style="width: 120px;"/>&nbsp;<input type="text" name="stock[]" placeholder="Stock" style="width: 120px;"/>&nbsp;<a href="javascript:void(0);" class="remove_button">Remove</div>'; //New input field html
    // var x = 1; //Initial field counter is 1

    // //Once add button is clicked
    // $(addButton).click(function () {
    //     //Check maximum number of input fields
    //     if (x < maxField) {
    //         x++; //Increment field counter
    //         $(wrapper).append(fieldHTML); //Add field html
    //     }
    // });

    // //Once remove button is clicked
    // $(wrapper).on('click', '.remove_button', function (e) {
    //     e.preventDefault();
    //     $(this).parent('div').remove(); //Remove field html
    //     x--; //Decrement field counter
    // });


});
