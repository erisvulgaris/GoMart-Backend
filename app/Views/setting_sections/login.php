                        btn.innerText = 'Activate';
                    }
                },
                error: function() {
                    toastr.error('Request failed. Try again.');
                    btn.disabled = false;
                    btn.innerText = 'Activate';
                }
            });
        }
    </script>
</body>

</html>