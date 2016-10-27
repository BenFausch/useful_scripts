<!-- ****REMOVE SPACES IN ALL FILES IN CURRENT DIRECTORY -->
for f in *\ *; do mv "$f" "${f// /_}"; done