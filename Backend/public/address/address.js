
            const provinceEl = document.getElementById("province");
            const districtEl = document.getElementById("district");
            const wardEl = document.getElementById("ward");
            const fullAddressInput = document.getElementById("fullAddress");

            // Load dữ liệu tỉnh/thành từ file JSON (bạn có thể thay bằng API nếu muốn)
            fetch("https://provinces.open-api.vn/api/?depth=3")
                .then(res => res.json())
                .then(data => {
                    data.forEach(province => {
                        const opt = document.createElement("option");
                        opt.value = province.code;
                        opt.textContent = province.name;
                        provinceEl.appendChild(opt);
                    });

                    // Khi chọn tỉnh
                    provinceEl.addEventListener("change", function() {
                        districtEl.innerHTML = '<option disabled selected>Quận/Huyện</option>';
                        wardEl.innerHTML = '<option disabled selected>Phường/Xã</option>';
                        wardEl.disabled = true;

                        const selectedProvince = data.find(p => p.code == this.value);
                        districtEl.disabled = false;

                        selectedProvince.districts.forEach(district => {
                            const opt = document.createElement("option");
                            opt.value = district.code;
                            opt.textContent = district.name;
                            districtEl.appendChild(opt);
                        });

                        districtEl.onchange = function() {
                            wardEl.innerHTML = '<option disabled selected>Phường/Xã</option>';
                            const selectedDistrict = selectedProvince.districts.find(d => d.code == this.value);
                            wardEl.disabled = false;

                            selectedDistrict.wards.forEach(ward => {
                                const opt = document.createElement("option");
                                opt.value = ward.code;
                                opt.textContent = ward.name;
                                wardEl.appendChild(opt);
                            });

                            wardEl.onchange = function() {
                                const provinceName = provinceEl.options[provinceEl.selectedIndex].text;
                                const districtName = districtEl.options[districtEl.selectedIndex].text;
                                const wardName = wardEl.options[wardEl.selectedIndex].text;
                                fullAddressInput.value = `${wardName}, ${districtName}, ${provinceName}`;
                            };
                        };
                    });
                });
